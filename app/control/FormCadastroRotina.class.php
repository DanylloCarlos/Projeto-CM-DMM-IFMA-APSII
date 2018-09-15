<?php
/**
 * FormCadastroRotina
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class FormCadastroRotina extends TPage
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
        $freq        = new TEntry('freq'); //password
        $desc        = new TEntry('desc');
        $dtIni       = new TDateTime('dtini'); //created TDate
        $dtFim       = new TDate('dtfim'); //expires TDate
        $resp        = new TCombo('resp'); //value
//         $local       = new TEntry('local'); //TColor color
//         $qtd         = new TEntry('quantidade'); //TSpinner weight
//         $type        = new TCombo('type'); //TCombo type
//         $text        = new TText('text');
//         
//        $id->setEditable(FALSE);
        $dtIni->setMask('dd/mm/yyyy hh:ii');
        $dtFim->setMask('dd/mm/yyyy');
        $dtIni->setDatabaseMask('dd-mm-yyyy hh:ii');
        $dtFim->setDatabaseMask('dd-mm-yyyy');
        
//         $value->setNumericMask(2, ',', '.', true);
//         $value->setSize('100%');
//         $color->setSize('100%');
//         $created->setSize('100%');
//         $expires->setSize('100%');
//         $weight->setRange(1,100,0.1);
//         $weight->setSize('100%');


///////////////////////////////////////////////////////////////
/**
buscar do banco para carregar a combo
**/
         //  $resp->setSize('100%');
        $conn = new PDO("mysql:host=localhost;port=3306;dbname=permission", 'root', '');
        $dados=$conn->prepare("SELECT name FROM system_user;");
        $dados->execute();    
        $lista = $dados->fetchAll(PDO::FETCH_ASSOC);
        
       //  $elementos = $lista[0]["nome"];
         
         $elementos = array();
         
         $size = count($lista);
         
         $z = 1;
         for($i=0; $i < $size-1; $i++){
              
              $elementos[$i] = $lista[$z]["name"];
              echo "<br>";
      //        var_dump($elementos[$i]);
         
              $z++;
         
         }
//          echo "<br><br>";
//          echo "teste elementos no array elementos <br><br>";         
//          var_dump($elementos);
//          echo "<br>";
//          
        
//          while($row = $lista){
//              
//              $arrayNomes = $row["nome"];
//              
//          }
         
        $resp->addItems($elementos);
             
         
///////////////////////////////////////////////////////////////

//         
        $dtIni->setValue( date('d-m-Y H:i') );
        $dtFim->setValue( date('d-m-Y', strtotime("+1 days")) );
//         $value->setValue(123.45);
//         $weight->setValue(30);
//         $color->setValue('#FF0000');
//         $type->setValue('a');
//         
        // add the fields inside the form
 //       $this->form->addFields( [new TLabel('Id')],            [$id]   );
        $this->form->addFields( [new TLabel('Nome')],          [$nome] );
        $this->form->addFields( [new TLabel('Frequência')],    [$freq] );
        $this->form->addFields( [new TLabel('Descrição')],     [$desc] );
        $this->form->addFields( [new TLabel('Inicio')],        [$dtIni], 
                                [new TLabel('Término')],       [$dtFim]);
        $this->form->addFields( [new TLabel('Responsável')],   [$resp]);
//                                 [new TLabel('Quantidade')],       [$qtd]);
//         $this->form->addFields( [new TLabel('Local')],      [$local]);
        
        $freq->placeholder = 'Por ex.: Diário, Semanal, Mensal, Anual';
       // $marca->setTip('Romi, Nardini, GBH...');
        $desc->placeholder = 'Uma vez ao dia, Duas vezes por semana...';
         
        $label = new TLabel('', '#7D78B6', 8, 'bi');
        $label->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent( [$label] );
        
//         $this->form->addFields( [new TLabel('Description')], [$text] );
//         $text->setSize('100%', 50);
        
        // define the form action 
        $this->form->addAction('Cadastrar Rotina', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
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
        
        $connS = new PDO("mysql:host=localhost;port=3306;dbname=permission", 'root', '');
        
            $sqlSelect = "SELECT id FROM system_user where name=?";
           
            
            $stmtS=$connS->prepare($sqlSelect);
            $stmtS->bindParam(1, $data->nome, PDO::PARAM_STR, 45);        
            $stmtS->execute();
            $idResp = $stmtS->fetch(PDO::FETCH_ASSOC);
         
         $sqlInsert = "INSERT INTO rotina (dtIni, dtFim, freq, nome, descricao, idResp)
                                 values(?, ?, ?, ?, ?, ?)";
    
             $idResp = $data->resp;
             $idResp = $idResp + 1;
                
                    
        $connI = new PDO("mysql:host=localhost;port=3306;dbname=bd_cr2m", 'root', '');
         
         $stmtI=$connI->prepare($sqlInsert);
            
             $stmtI->bindParam(1, $data->dtini, PDO::PARAM_STR, 45);
             $stmtI->bindParam(2, $data->dtfim, PDO::PARAM_STR, 45);
             $stmtI->bindParam(3, $data->freq, PDO::PARAM_STR, 45);
             $stmtI->bindParam(4, $data->nome, PDO::PARAM_STR, 45);
             $stmtI->bindParam(5, $data->desc, PDO::PARAM_STR, 45);
             $stmtI->bindParam(6, $idResp, PDO::PARAM_INT, 11);
             
             $stmtI->execute();
             
//         $data = $this->form->getData();
//         
//         // put the data back to the form
//         $this->form->setData($data);
        
        // creates a string with the form element's values
        $str = "<br><br>Dados adicionados:";
        
        $message = 'Inserção bem sucedida!  ' . $str . '<br><br>';
        $message = 'Id: '           . $data->nome . '<br>';
        $message.= 'Inicio : ' . $data->dtini . '<br>';
        $message.= 'Fim : '    . $data->dtfim . '<br>';
        $message.= 'Frequência: '      . $data->freq . '<br>';
        $message.= 'Descrição: '      . $data->desc . '<br>';
        $message.= 'Id do Resp. : '       . $idResp . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}