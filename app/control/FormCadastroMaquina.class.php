<?php
/**
 * FormBuilderView
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormCadastroMaquina extends TPage
{
    private $form;
    
    /**
     * Class constructor
     * Creates the page
     */
    function __construct()
    {
        parent::__construct();
        
        // create the form
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle(_t('')); //_t('')
        
        // create the form fields
//        $id          = new TEntry('id');
        $nome        = new TEntry('nome'); //description
        $marca       = new TEntry('marca'); //password
        $modelo      = new TEntry('modelo'); //created TDate
        $dim         = new TEntry('dim'); //expires TDate
        $pot         = new TEntry('pot'); //value
        $local       = new TEntry('local'); //TColor color
        $qtd         = new TEntry('qtd'); //TSpinner weight
//         $type        = new TCombo('type'); //TCombo type
//         $text        = new TText('text');
//         
//        $id->setEditable(FALSE);
//         $created->setMask('dd/mm/yyyy hh:ii');
//         $expires->setMask('dd/mm/yyyy');
//         $created->setDatabaseMask('yyyy-mm-dd hh:ii');
//         $expires->setDatabaseMask('yyyy-mm-dd');
//         $value->setNumericMask(2, ',', '.', true);
//         $value->setSize('100%');
//         $color->setSize('100%');
//         $created->setSize('100%');
//         $expires->setSize('100%');
//         $weight->setRange(1,100,0.1);
//         $weight->setSize('100%');
//         $type->setSize('100%');
//         $type->addItems( ['a' => 'Type a', 'b' => 'Type b', 'c' => 'Type c'] );
//         
//         $created->setValue( date('Y-m-d H:i') );
//         $expires->setValue( date('Y-m-d', strtotime("+1 days")) );
//         $value->setValue(123.45);
//         $weight->setValue(30);
//         $color->setValue('#FF0000');
//         $type->setValue('a');
//         
        // add the fields inside the form
//        $this->form->addFields( [new TLabel('Id')],          [$id]);
        $this->form->addFields( [new TLabel('Nome')], [$nome] );
        $this->form->addFields( [new TLabel('Marca')],    [$marca] );
        $this->form->addFields( [new TLabel('Modelo')],  [$modelo], 
                                [new TLabel('Dimensão')],  [$dim]);
        $this->form->addFields( [new TLabel('Potência')],       [$pot],
                                [new TLabel('Quantidade')],       [$qtd]);
        $this->form->addFields( [new TLabel('Local')],      [$local]);
        
        $nome->placeholder = 'Por ex.: Máquina de Torque Hidráulica';
        $marca->setTip('Romi, Nardini, GBH...');
        
        $label = new TLabel('', '#7D78B6', 8, 'bi');
        $label->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent( [$label] );
        
//         $this->form->addFields( [new TLabel('Description')], [$text] );
//         $text->setSize('100%', 50);
        
        // define the form action 
        $this->form->addAction('Cadastrar Máquina', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
//         $this->form->addHeaderAction('Cadastrar ', new TAction(array($this, 'onSend')), 'fa:rocket orange');
//         
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);

        parent::add($vbox);
    }
    
    /**
     * Simulates an save button
     * Show the form content
     */
    public function onSend($param)
    {
        
        $data = $this->form->getData();
        
        /*
            Salvar máquina no BD
        */
        
         $sqlInsert = "INSERT INTO maquina(nome, marca, modelo, dimensao, potencia, qtd, local)
                                 values(?, ?, ?, ?, ?, ?, ?)";
    
         $connI = new PDO("mysql:host=localhost;port=3306;dbname=bd_cr2m", 'root', '');
         
         $stmtI=$connI->prepare($sqlInsert);
            
             $stmtI->bindParam(1, $data->nome, PDO::PARAM_STR, 45);
             $stmtI->bindParam(2, $data->marca, PDO::PARAM_STR, 45);
             $stmtI->bindParam(3, $data->modelo, PDO::PARAM_STR, 45);
             $stmtI->bindParam(4, $data->dim, PDO::PARAM_STR, 45);
             $stmtI->bindParam(5, $data->pot, PDO::PARAM_STR, 45);
             $stmtI->bindParam(6, $data->qtd, PDO::PARAM_STR, 45);
             $stmtI->bindParam(7, $data->local, PDO::PARAM_STR, 45);
             
             $stmtI->execute();
      
        $str = "<br><br>Dados adicionados: ";
        // put the data back to the form
        $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'Inserção bem sucedida!' . $str . '<br><br>';
        $message.= 'Nome : ' . $data->nome . '<br>';
        $message.= 'Marca : '    . $data->marca . '<br>';
        $message.= 'Modelo: '      . $data->modelo . '<br>';
        $message.= 'Modelo: '      . $data->dim . '<br>';
        $message.= 'Modelo: '      . $data->pot . '<br>';
        $message.= 'Potência: '      . $data->local . '<br>';
        $message.= 'Local : '       . $data->qtd . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}