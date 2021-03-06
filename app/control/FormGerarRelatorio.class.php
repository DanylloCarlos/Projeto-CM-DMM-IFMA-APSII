<?php
/**
 * PDF Designed Customer report
 *
 * @version    1.0
 * @package    samples
 * @subpackage tutor
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class FormGerarRelatorio extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form and a inner table
        $this->form = new TForm('form_pdf_report');
        $table = new TTable;
        $this->form->add($table);
        // creates an action button
        $save_button=new TButton('generate');
        $save_button->setAction(new TAction(array($this, 'onGenerate')), 'Generate');
        $save_button->setImage('ico_save.png');
        // add a row for the form action
        $table->addRowSet($save_button);
        // define wich are the form fields
        $this->form->setFields(array($save_button));
        
        // wrap the page content using vertical box
        $vbox = new TVBox;
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        parent::add($vbox);
    }
    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'samples'
            TTransaction::open('samples');
            
            // load all customers
            $repository = new TRepository('Customer');
            $criteria   = new TCriteria;
            $customers = $repository->load($criteria);
            
            $data = $this->form->getData();
            $this->form->validate();
            
            $designer = new TPDFDesigner;
            $designer->fromXml('app/reports/report.pdf.xml');
            $designer->generate();
            
            $fill = TRUE;
            $designer->gotoAnchorXY('details');
            $designer->SetFont('Arial', '', 10);
            $designer->setFillColorRGB( '#F9F9FF' );
            
            if ($customers)
            {
                foreach ($customers as $customer)
                {
                    $designer->gotoAnchorX('details');
                    $designer->Cell( 34, 12, $customer->id, 1, 0, 'C', $fill);
                    $designer->Cell(160, 12, utf8_decode($customer->name), 1, 0, 'L', $fill);
                    $designer->Cell(152, 12, utf8_decode($customer->address), 1, 0, 'L', $fill);
                    $designer->Cell(152, 12, utf8_decode($customer->city_name), 1, 0, 'L', $fill);
                    $designer->Ln(12);
                    
                    // grid background
                    $fill = !$fill;
                }
            }
            
            $file = 'app/output/pdf_report.pdf';
            
            if (!file_exists($file) OR is_writable($file))
            {
                $designer->save($file);
                parent::openFile($file);
            }
            else
            {
                throw new Exception(_t('Permission denied') . ': ' . $file);
            }
            
            new TMessage('info', 'Report generated. Please, enable popups in the browser (just in the web).');
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            TTransaction::rollback();
        }
    }
}
?>