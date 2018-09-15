<?php
/**
 * DelOuAtOS
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class DelOuAtOS extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        // create the datagrid columns
        $nome       = new TDataGridColumn('nome',    'Nome',    'left',   '30%'); //name
        $dtAber    = new TDataGridColumn('dtAbert', 'DT ABER', 'left',   '20%'); //address
        $dtFech  = new TDataGridColumn('dtFech',    'DT FECH',   'left',   '30%'); //telephone
        $nomeResp = new TDataGridColumn('nresp', 'Resp.', 'left', '30%'); //novo
        
      //  $telephone->setDataProperty('hiddable', 400);
        
        // add the columns to the datagrid
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($dtAber);
        $this->datagrid->addColumn($dtFech);
        $this->datagrid->addColumn($nomeResp); //novo
        
        // creates two datagrid actions
        $action1 = new TDataGridAction(array($this, 'onView'));
        $action1->setLabel('Atualizar');
        $action1->setImage('bs:hand-right green');
        $action1->setField('nome');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel('Remover');
        $action2->setImage('bs:remove red');
        $action2->setField('nome');
//         
//         $action3 = new TDataGridAction(array($this, 'onView'));
//         $action3->setLabel('View address');
//         $action3->setImage('bs:hand-right green');
//         $action3->setField('address');
        
        $action_group = new TDataGridActionGroup('Ações', 'bs:th');
        
        $action_group->addHeader('Opções Disponíveis');
        $action_group->addAction($action1);
        $action_group->addAction($action2);
       //  $action_group->addSeparator();
//         $action_group->addHeader('Another Options');
//         $action_group->addAction($action3);
//         
        // add the actions to the datagrid
        $this->datagrid->addActionGroup($action_group);
        
        // creates the datagrid model
        $this->datagrid->createModel();
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add(TPanelGroup::pack(_t('Gerenciamento de Ordens de Serviço'), $this->datagrid, 'Área de gerenciamento'));

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
    
           $this->datagrid->clear();
        
        $sqlOS = "SELECT nome, dtAbert, dtFech, idResp FROM OS";
        $sqlIdResp = "SELECT idResp from OS";
        $sqlNomeResp = "SELECT name FROM system_user where id= :OS_idResp";
        $connRotina = new PDO("mysql:host=localhost; port=3306; dbname=bd_cr2m", 'root', '');
        $connSysUser = new PDO("mysql:host=localhost; port=3306; dbname=permission", 'root', '');
        
           /*
             Recuperando dados da OS
           */
           $stmtOS = $connRotina->prepare($sqlOS);
           $stmtOS->execute();
           $resultSetOS = $stmtOS->fetchAll(PDO::FETCH_ASSOC);
            
           $arrayOS = array();
           $arrayOS = $resultSetOS;
           $sizeOS = count($resultSetOS);
        
           /*
             Recuperando nomes dos resp. das rotinas
           */
         
           $stmtIdResp = $connRotina->prepare($sqlIdResp);
           $stmtIdResp->execute();
           $resultSetIdsResp = $stmtIdResp->fetchAll(PDO::FETCH_ASSOC);
                    
           $sizeIdsResp = count($resultSetIdsResp);
          // var_dump($sizeIdsResp);
           $arrayIdsResp = array();
           $z = 0;
           for($i = 0; $i < $sizeIdsResp; $i++){
           
           $arrayIdsResp[$i] = intval($resultSetIdsResp[$z]["idResp"]) + 1;
           $z++;
           }
           
           $arrayNomesResp = array();
           for($i=0; $i<$sizeIdsResp; $i++){ 
          
           $stmtNomeResp = $connSysUser->prepare($sqlNomeResp);
           $stmtNomeResp->bindParam(':OS_idResp', $arrayIdsResp[$i], PDO::PARAM_INT);
           $stmtNomeResp->execute();
           $resultSetNomeResp = $stmtNomeResp->fetch(PDO::FETCH_ASSOC);
           
           $arrayNomeResp[$i] = $resultSetNomeResp;
          }
          
        /*
          Adicionando dados da OS
        */
           for ($i = 0; $i < $sizeOS; $i++){
               
               $item = new StdClass;
               $item->nome = $arrayOS[$i]["nome"];
               $item->dtAbert = $arrayOS[$i]["dtAbert"];
               $item->dtFech = $arrayOS[$i]["dtFech"];
               $item->nresp = $arrayNomeResp[$i]["name"];
               $this->datagrid->addItem($item);
           }
     }

    /**
     * method onDelete()
     * Executed when the user clicks at the delete button
     */
    function onDelete($param)
    {
        // get the parameter and shows the message
        $key=$param['key'];
        new TMessage('error', "The register $key may not be deleted");
    }
    
    /**
     * method onView()
     * Executed when the user clicks at the view button
     */
    function onView($param)
    {
        // get the parameter and shows the message
        $key=$param['key'];
        new TMessage('info', "Nome: $key");
    }
    
    /**
     * shows the page
     */
    function show()
    {
        $this->onReload();
        parent::show();
    }
}
