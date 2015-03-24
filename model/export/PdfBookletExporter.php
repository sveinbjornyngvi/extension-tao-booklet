<?php
/**  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA;
 *               
 */

namespace oat\taoBooklet\model\export;

use mikehaertl\wkhtmlto\Pdf;

/**
 * PdfBookletExporter provides functionality to export booklet to 
 * PDF format (directly send to end user or save into pdf file).
 *
 * @author Aleh Hutnikau <hutnikau@1pt.com>
 */
class PdfBookletExporter extends BookletExporter
{
    /**
     * @var Pdf instance of the wrapper around wkhtmltopdf
     */
    protected $pdf;
    
    public function __construct()
    {
        if (!$this->isWkhtmltopdfInstalled()) {
            throw new BookletExporterException('wkhtmltopdf tool is not installed'); 
        }
        $this->pdf = new Pdf;
    }
    
    /**
     * Send PDF to client, either inline display or as download a file
     * 
     * @param string|null $filename the filename to send. If empty, the PDF is streamed inline.
     * @return bool whether PDF was created successfully
     */
    public function export($filename = null) 
    {
        $this->pdf->addPage($this->_content);
        return $this->pdf->send($filename);
    }
    
    /**
     * Set booklet content
     * 
     * @param string $content either a URL, a HTML string or a PDF/HTML filename
     * @return PdfBookletExporter instance for method chaining
     * @throws BookletExporterException if wrong $content parameter passed
     */
    public function setContent($content) 
    {   
        $result = '';
        if (filter_var($content, FILTER_VALIDATE_URL)) { //url
            $opts = array( 
                'http'=>array( 
                    'method'=>"GET",
                    'Cookie: ' . session_name() . '=' . session_id() . "\r\n" 
                ) 
            );
            $context = stream_context_create($opts);
            $result = $file = file_get_contents($content, false, $context);
        } elseif (file_exists($content) && is_file($content)) {  //file path
            $result = file_get_contents($content);
        } else { //HTML string
            $result = $content;
        }
        if (is_string($result)) {
            $this->_content = $result;
            return $this;
        }
        throw new BookletExporterException('Wrong content type');
    }
    
    /**
     * Set Pdf option(s)
     *
     * @param array $options list of global PDF options to set as name/value pairs
     * @return PdfBookletExporter instance for method chaining
     */
    public function setOptions($options = array()) {
        $this->pdf->setOptions($options);
        return $this;
    }
    
    /**
     * Save booklet into file
     * 
     * @param string $path the file path
     * @return bool whether file was created successfully
     */
    public function saveAs($path)
    {
        $this->pdf->addPage($this->_content);
        return $this->pdf->saveAs($path);
    }
    
    /**
     * @return string the detailed error message. Empty string if none.
     */
    public function getError()
    {
        return $this->pdf->getError();
    }
    
    /**
     * @return whether wkhtmltopdf tool is installed
     */
    public function isWkhtmltopdfInstalled()
    {
        $shellOutput = `wkhtmltopdf -V`;
        return preg_match('/wkhtmltopdf\s\d\..*/', $shellOutput);
    }
}
