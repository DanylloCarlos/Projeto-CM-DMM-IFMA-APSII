<?php
/**
 * FormCadastroItem
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class FormCadastroItem extends TPage
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
        //$id          = new TEntry('id');
        $nome        = new TEntry('nome'); //description
        $desc        = new TEntry('desc'); //password
        $qtd         = new TEntry('qtd');
        $peca        = new TCheckButton("peca");
        $ferr        = new TCheckButton("ferramenta");
        
        $radio = new TRadioGroup('enable');
        $radio->addItems(array('1'=>'Peça', '2'=>'Ferramenta'));
        $radio->setLayout('horizontal');
        $radio->setValue(1);
        
        
        
        // add the fields inside the form
        $this->form->addFields( [new TLabel('Nome')],         [$nome] );
        $this->form->addFields( [new TLabel('Descrição')],    [$desc] );
        $this->form->addFields( [new TLabel('Quantidade')],   [$qtd]  );
        $this->form->addFields( [new TLabel('')],      [$radio] ); 
        
        $nome->placeholder = 'Por ex.: Alicate crimpador';
       // $marca->setTip('Romi, Nardini, GBH...');
        $desc->placeholder = 'Por ex.: Alicate de 9 pol da Tramontina PRO';
         
        $label = new TLabel('', '#7D78B6', 8, 'bi');
        $label->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent( [$label] );
        
//         $this->form->addFields( [new TLabel('Description')], [$text] );
//         $text->setSize('100%', 50);
        
        // define the form action 
        $this->form->addAction('Cadastrar Item', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
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
        // $this->form->setData($data);
        
         $sqlInsert = "INSERT INTO item (nome, descricao, qtd)
                                 values(?, ?, ?)";
    
        $connI = new PDO("mysql:host=localhost;port=3306;dbname=bd_cr2m", 'root', '');
         
         $stmtI=$connI->prepare($sqlInsert);
            
             $stmtI->bindParam(1, $data->nome, PDO::PARAM_STR, 45);
             $stmtI->bindParam(2, $data->desc, PDO::PARAM_STR, 45);
             $stmtI->bindParam(3, $data->qtd, PDO::PARAM_INT, 11);
             
             $stmtI->execute();
             
//         $data = $this->form->getData();
//         
//         // put the data back to the form
//         $this->form->setData($data);
        
        // creates a string with the form element's values
        $message = 'Nome: '           . $data->nome . '<br>';
        $message.= 'Descrição : ' . $data->desc . '<br>';
        $message.= 'Quantidade : '    . $data->qtd . '<br>';
        
        // show the message
        new TMessage('Inserção bem sucedida!', $message);
    }
}