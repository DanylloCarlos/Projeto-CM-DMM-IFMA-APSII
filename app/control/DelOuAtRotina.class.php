<?php
/**
 * DelOuAtRotina
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class DelOuAtRotina extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        // create the datagrid columns
        $nome       = new TDataGridColumn('nome',    'Nome',    'left',   '30%'); //name
        $dtIni    = new TDataGridColumn('dtini', 'DT INI', 'left',   '20%'); //address
        $dtFim  = new TDataGridColumn('dtfim',    'DT FIM',   'left',   '30%'); //telephone
        $nomeResp = new TDataGridColumn('nresp', 'Resp.', 'left', '30%'); //novo
        
      //  $telephone->setDataProperty('hiddable', 400);
        
        // add the columns to the datagrid
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($dtIni);
        $this->datagrid->addColumn($dtFim);
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
        $vbox->add(TPanelGroup::pack(_t('Gerenciamento de Rotinas'), $this->datagrid, 'Área de gerenciamento'));

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
    
           $this->datagrid->clear();
        
        $sqlNome = "SELECT nome FROM rotina";
        $sqlDtIni = "SELECT dtIni FROM rotina";
        $sqlDtFim = "SELECT dtFim FROM rotina";
        $sqlIdResp = "SELECT idResp FROM rotina";
        $sqlNomeResp = "SELECT name FROM system_user where id= :Rotina_idResp";
        $connSysUser = new PDO("mysql:host=localhost; port=3306; dbname=permission", 'root', '');
        $connRotina = new PDO("mysql:host=localhost; port=3306; dbname=bd_cr2m", 'root', '');
        
        /*
          Recuperando nomes das rotinas
        */
        $stmtNome = $connRotina->prepare($sqlNome);
        $stmtNome->execute();
        $resultSetNomes = $stmtNome->fetchAll(PDO::FETCH_ASSOC);
        
        $sizeNomes = count($resultSetNomes);
        $arrayNomesRotina = array();
        $z = 0;
        for($i = 0; $i < $sizeNomes; $i++){
        
        $arrayNomesRotina[$i] = $resultSetNomes[$z]["nome"];
        $z++;
        }
        
         /*
           Recuperando datas de inicio das rotinas
         */
           $stmtDtIni = $connRotina->prepare($sqlDtIni);
           $stmtDtIni->execute();
           $resultSetDtsIni = $stmtDtIni->fetchAll(PDO::FETCH_ASSOC);
           
           $sizeDtsIni = count($resultSetDtsIni);
           $arrayDtsIni = array();
           $z = 0;
           for($i = 0; $i < $sizeDtsIni; $i++){
         
           $arrayDtsIni[$i] = $resultSetDtsIni[$z]["dtIni"];
             
           $z++;
           }
         
           /*
             Recuperando datas fim das rotinas
           */
           $stmtDtFim = $connRotina->prepare($sqlDtFim);
           $stmtDtFim->execute();
           $resultSetDtsFim = $stmtDtFim->fetchAll(PDO::FETCH_ASSOC);
          
           $sizeDtsFim = count($resultSetDtsFim);
           $arrayDtsFim = array();
           $z = 0;
           for($i = 0; $i < $sizeDtsFim; $i++){
          
           $arrayDtsFim[$i] = $resultSetDtsFim[$z]["dtFim"];
          
           $z++;
           }

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
           $stmtNomeResp->bindParam(':Rotina_idResp', $arrayIdsResp[$i], PDO::PARAM_INT);
           $stmtNomeResp->execute();
           $resultSetNomeResp = $stmtNomeResp->fetch(PDO::FETCH_ASSOC);
           
           $arrayNomeResp[$i] = $resultSetNomeResp;
          }
          
        /*
          Adicionando os nomes das rotinas
        */
           for ($i = 0; $i < $sizeNomes; $i++){
               
               $item = new StdClass;
               $item->nome = $arrayNomesRotina[$i];
               $item->dtini = $arrayDtsIni[$i];
               $item->dtfim = $arrayDtsFim[$i];
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
        new TMessage('info', "Nome da rotina: $key");
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
