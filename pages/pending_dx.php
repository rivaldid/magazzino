<?php
echo atitolo."TASKS".ctitolo.accapo;
echo adesc."documenti dichiarati nei tasks, ma non ancora acquisiti in registro".cdesc.accapo;
echo table_NumOrfani();
echo atitolo."DOCS".ctitolo.accapo;
echo adesc."documenti gia acquisiti in registro, ma non dichiarati in alcun task".cdesc.accapo;
echo table_DocOrfani();
?>
