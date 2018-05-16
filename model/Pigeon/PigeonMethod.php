<?php
    include_once "enumeration/Enumeration.php";
    Class PigeonMethod extends Enumeration{
        const DEFAULT = null;
        const INSERT = "insert";
        const ARRAYINSERT = "arrayInsert";
        const UPDATE = "update";
        const DELETE = "delete";
        const UNIQUERETURN = "returnUnique";
        const ARRAYRETURN = "returnArray";
    }
?>