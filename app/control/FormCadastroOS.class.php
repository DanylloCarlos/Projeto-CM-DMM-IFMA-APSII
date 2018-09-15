<?php
/**
 * FormCadastroOS
 *
 * @version    1.0
 * @package    
 * @subpackage 
 * @author     Danyllo C.
 */
class FormCadastroOS extends TPage
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
        $local        = new TEntry('local');
        $dtAbert       = new TDateTime('dtFech'); //created TDate
        $dtFech       = new TDateTime('dtAbert'); //expires TDate
        $resp        = new TCombo('resp'); //value
//         $local       = new TEntry('local'); //TColor color
//         $qtd         = new TEntry('quantidade'); //TSpinner weight
//         $type        = new TCombo('type'); //TCombo type
//         $text        = new TText('text');
//         
      //  $id->setEditable(FALSE);
        $dtFech->setMask('dd/mm/yyyy hh:ii');
        $dtAbert->setMask('dd/mm/yyyy hh:ii');
        $dtFech->setDatabaseMask('dd-mm-yyyy hh:ii');
        $dtAbert->setDatabaseMask('dd-mm-yyyy hh:ii');
        
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
        $dtAbert->setValue( date('d-m-Y H:i') );
        $dtFech->setValue( date('d-m-Y', strtotime("+1 days")) );
//         $value->setValue(123.45);
//         $weight->setValue(30);
//         $color->setValue('#FF0000');
//         $type->setValue('a');
//         
        // add the fields inside the form
     //   $this->form->addFields( [new TLabel('Id')],            [$id]   );
        $this->form->addFields( [new TLabel('Nome')],                    [$nome] );
        $this->form->addFields( [new TLabel('Descrição')],               [$desc] );
        $this->form->addFields( [new TLabel('Local')],                   [$local] );
        $this->form->addFields( [new TLabel('DT ABER')],        [$dtAbert], 
                                [new TLabel('DT FECH')],      [$dtFech]);
        $this->form->addFields( [new TLabel('Responsável')],             [$resp]);
//                                 [new TLabel('Quantidade')],       [$qtd]);
//         $this->form->addFields( [new TLabel('Local')],      [$local]);
        
        $local->placeholder = 'Por ex.: Pátio de Metalurgia, Laboratório de Mecânica...';
       // $marca->setTip('Romi, Nardini, GBH...');
        $desc->placeholder = 'Por ex.: Manutenção realizada com ciência do cliente X...';
         
        $label = new TLabel('', '#7D78B6', 8, 'bi');
        $label->style='text-align:left;border-bottom:1px solid #c0c0c0;width:100%';
        $this->form->addContent( [$label] );
        
//         $this->form->addFields( [new TLabel('Description')], [$text] );
//         $text->setSize('100%', 50);
        
        // define the form action 
        $this->form->addAction('Cadastrar OS', new TAction(array($this, 'onSend')), 'fa:check-circle-o green');
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
         
         $sqlInsert = "INSERT INTO OS (nome, descricao, local, dtAbert, dtFech, idResp)
                                 values(?, ?, ?, ?, ?, ?)";
    
             $idResp = $data->resp;
             $idResp = $idResp + 1;
                
                    
        $connI = new PDO("mysql:host=localhost;port=3306;dbname=bd_cr2m", 'root', '');
         
         $stmtI=$connI->prepare($sqlInsert);
            
             $stmtI->bindParam(1, $data->nome, PDO::PARAM_STR, 45);
             $stmtI->bindParam(2, $data->desc, PDO::PARAM_STR, 45);
             $stmtI->bindParam(3, $data->local, PDO::PARAM_STR, 45);
             $stmtI->bindParam(4, $data->dtAbert, PDO::PARAM_STR, 45);
             $stmtI->bindParam(5, $data->dtFech, PDO::PARAM_STR, 45);
             $stmtI->bindParam(6, $idResp, PDO::PARAM_INT, 11);
             
             $stmtI->execute();
             
//         $data = $this->form->getData();
//         
//         // put the data back to the form
//         $this->form->setData($data);
        
         $str = "<br><br>Dados adicionados: ";
        
        // creates a string with the form element's values
        $message = 'Inserção bem sucedida!' . $str . '<br><br>';
        $message = 'Nome: '           . $data->nome . '<br>';
        $message.= 'Descrição : ' . $data->desc . '<br>';
        $message.= 'Local : '    . $data->local . '<br>';
        $message.= 'DT INI: '      . $data->dtAbert . '<br>';
        $message.= 'DT FIM: '      . $data->dtFech . '<br>';
        $message.= 'Nome Resp. : '       . $idResp . '<br>';
        
        // show the message
        new TMessage('info', $message);
    }
}