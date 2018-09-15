<?php
/**
 * DelOuAtMaquina
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class DelOuAtMaquina extends TPage
{
    private $datagrid;
    
    public function __construct()
    {
        parent::__construct();
        
        // creates one datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        
        // create the datagrid columns
        $nome       = new TDataGridColumn('nome',    'Nome',    'left',   '30%'); //name
        $modelo    = new TDataGridColumn('modelo', 'Modelo', 'center',   '15%'); //address
        $qtd  = new TDataGridColumn('qtd',    'Quantidade',   'center',   '15%'); //telephone
        $local = new TDataGridColumn('local', 'Local', 'left', '25%'); //novo
        
      //  $telephone->setDataProperty('hiddable', 400);
        
        // add the columns to the datagrid
        $this->datagrid->addColumn($nome);
        $this->datagrid->addColumn($modelo);
        $this->datagrid->addColumn($qtd);
        $this->datagrid->addColumn($local); //novo
        
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
        $vbox->add(TPanelGroup::pack(_t('Gerenciamento de Máquinas'), $this->datagrid, 'Área de gerenciamento'));

        parent::add($vbox);
    }
    
    /**
     * Load the data into the datagrid
     */
    function onReload()
    {
    
           $this->datagrid->clear();
        
        $sql = "SELECT nome, modelo, qtd, local FROM maquina";
        $conn = new PDO("mysql:host=localhost; port=3306; dbname=bd_cr2m", 'root', '');
        
        /*
          Recuperando atributos da máquina para o datagrid
        */
        $arrayMaquina = array();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $arrayMaquina = $resultSet;    
          
          $sizeMaquinas = count($arrayMaquina);
        /*
          Adicionando os atributos no datagrid
        */
           for ($i = 0; $i < $sizeMaquinas; $i++){
               
               $item = new StdClass;
               $item->nome = $arrayMaquina[$i]["nome"];
               $item->modelo = $arrayMaquina[$i]["modelo"];
               $item->qtd = $arrayMaquina[$i]["qtd"];
               $item->local = $arrayMaquina[$i]["local"];
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
        new TMessage('info', "Nome da Máquina: $key");
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
