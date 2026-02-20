<?php
/**
 * XML to XHTML Transformation
 * Transforms employees.xml using employees.xsl stylesheet
 * Access at: http://localhost/soap_service/transform.php
 */

// Check if the XML file exists
$xmlFile = __DIR__ . '/employees.xml';
$xslFile = __DIR__ . '/employees.xsl';

if (!file_exists($xmlFile)) {
    die("Error: employees.xml file not found at $xmlFile");
}

if (!file_exists($xslFile)) {
    die("Error: employees.xsl file not found at $xslFile");
}

// Create DOMDocument objects
$xmlDoc = new DOMDocument();
$xslDoc = new DOMDocument();

// Load XML and XSL files
$xmlDoc->load($xmlFile);
$xslDoc->load($xslFile);

// Create XSLTProcessor
$xsltProc = new XSLTProcessor();
$xsltProc->importStyleSheet($xslDoc);

// Transform XML using XSL
$result = $xsltProc->transformToXML($xmlDoc);

if ($result === false) {
    echo "Error: Transformation failed";
    echo "Error: " . $xsltProc->getLastError();
} else {
    // Output the transformed XHTML
    header('Content-Type: text/html; charset=UTF-8');
    echo $result;
}
?>
