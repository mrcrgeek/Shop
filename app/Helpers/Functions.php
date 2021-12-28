<?php

function Check_PhoneNum_ir($PhoneNumber):bool
{
    $result = false;

    if(strlen($PhoneNumber) > 11 || strlen($PhoneNumber) < 11)
    {
        $result = false;
    }
    else
    {
        if(substr($PhoneNumber, 0,2) === '09')
        {
            $result = true;
        }
        else
        {
            $result = false;
        }
    }

    return $result;
}

function Set_Default_Value($Var, $Value)
{
    if($Var == null)
    {
        $Var = $Value;
    }

    return $Var;
}
?>
