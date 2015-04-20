<?php
$content = "some text here";
$fp = fopen("language.xml","wb");
fwrite($fp,$content);
fclose($fp);
?>
<language>
<navText>Text123</navText>
<navStyle>Style</navStyle>
<navIcon>Icons</navIcon>
<navColor>Colors</navColor>
<font1>Arial Black</font1>
<font2>Helvetica Neu</font2>
<font3>Capture It</font3>
<font4>Georgia</font4>
<font5>Verdana</font5>
<font6>Tekton Pro</font6>
<font7>Stencil</font7>
<font8>Impact</font8>
<font9>League Gothic</font9>
<font10>Lobster One</font10>
<font11>Rosewood</font11>
<font12>Myriad Pro</font12>
<font13>Myriad Pro</font13>
<textSaveOptions>Save Options</textSaveOptions>
<textColorOptions>Color Options</textColorOptions>
<textSaveAs>Save As</textSaveAs>
<textIcons>Icons</textIcons>
<textBackground>Background</textBackground>
<textSaveTo>Save To</textSaveTo>
<txtAlign>Align</txtAlign>
<txtField>Text Field</txtField>
<txtColor>Color</txtColor>
<txtSize>Size</txtSize>
<txtFont>Font</txtFont>
<txtButton>Button Text</txtButton>
<btnReset>Reset design area.</btnReset>
<btnDownload>Save graphic to computer.</btnDownload>
<btnCapture>Save graphic to gallery.</btnCapture>
<btnUpload>Upload graphic.</btnUpload>
<btnDelete>Delete object from design area.</btnDelete>
<btnBack>Move object to the back.</btnBack>
<btnFront>Move object to the front.</btnFront>
<btnAlignLeft>Align text to the left.</btnAlignLeft>
<btnAlignCenter>Align text to the center.</btnAlignCenter>
<btnAlignRight>Align text to the right.</btnAlignRight>
<btnAddText>Add additional text fields.</btnAddText>
</language>
</languages>