<?php

/**
 * Description of Bancos
 *
 * @author Sergio Pérez <sergio.perez@albatronic.com>
 * @copyright Informática ALBATRONIC, SL
 * @since 04-nov-2011
 *
 */
class Bancos extends BancosEntity {

    public function __toString() {
        return $this->getBanco();
    }

    /**
     * Borra un banco y sus oficinas
     *
     * @return boolean
     */
    public function erase() {

        $this->conecta();

        $oficinas = new BancosOficinas();

        if (is_resource($this->_dbLink)) {
            $query = "DELETE FROM {$this->_dataBaseName}.{$this->_tableName} WHERE `IDBanco`='{$this->IDBanco}'";
            if ($this->_em->query($query)) {
                //Borrar oficinas
                $query = "DELETE FROM {$oficinas->getDataBaseName()}.{$oficinas->getTableName()} where `IDBanco`='{$this->IDBanco}'";
                if (!$this->_em->query($query))
                    $this->_errores = $this->_em->getError();
            } else
                $this->_errores = $this->_em->getError();
            $this->_em->desConecta();
        }
        unset($this->_em);
        unset($oficinas);

        return (count($this->_errores) == 0);
    }

    /**
     * Valida una cuenta corriente.
     * Devuelve el digito de control si es correcta
     * En caso contrario devuelve un mensaje de error
     * 
     * @param string $b El código del banco
     * @param string $o El código de la oficina
     * @param string $c La cuenta corriente
     * @return string EL dígito de control calculado o error
     */
    public function ValidaCC($b, $o, $c) {

        //Validar Banco
        $banco = new Bancos();
        $rows = $banco->cargaCondicion("IDBanco","CodigoBanco='{$b}'");
        unset($banco);
        if ($$rows[0]['IDBanco'] == '')
            $this->_errores[] = "El banco indicado no existe.";

        //Validar Oficina
        $oficina = new BancosOficinas();
        $rows = $oficina->cargaCondicion("ID","IDBanco='{$b}' and IDOficina='{$o}'");
        unset($oficina);
        if ($rows[0]['ID'] == '')
            $this->_errores[] = "La Oficina bancaria indicada no existe.";

        if (strlen($c) < 10)
            $this->_errores[] = "La cuenta corriente debe tener 10 dígitos";

        $dc = ($b . $o . $c == '000000000000000000') ?
                $dc = '00' :
                $dc = $this->getDigitoControl($b . $o, $c);
        
        return($dc);
    }

    /**
     * Calcula y devuelve el dígito de control
     * 
     * @param string $IentOfi El codigo del banco y la oficina concatenados
     * @param type $InumCta E. número de cuenta
     * @return string El dígito de control
     */
    public Function getDigitoControl($IentOfi, $InumCta) {
        $APesos = Array(1, 2, 4, 8, 5, 10, 9, 7, 3, 6); // Array de "pesos"
        $DC1 = 0;
        $DC2 = 0;
        $x = 8;
        while ($x > 0) {
            $digito = $IentOfi[$x - 1];
            $DC1 = $DC1 + ($APesos[$x + 2 - 1] * ($digito));
            $x = $x - 1;
        }
        $Resto = $DC1 % 11;
        $DC1 = 11 - $Resto;
        if ($DC1 == 10)
            $DC1 = 1;
        if ($DC1 == 11)
            $DC1 = 0;              // Dígito control Entidad-Oficina

        $x = 10;
        while ($x > 0) {
            $digito = $InumCta[$x - 1];
            $DC2 = $DC2 + ($APesos[$x - 1] * ($digito));
            $x = $x - 1;
        }
        $Resto = $DC2 % 11;
        $DC2 = 11 - $Resto;
        if ($DC2 == 10)
            $DC2 = 1;
        if ($DC2 == 11)
            $DC2 = 0;         // Dígito Control C/C

        $DigControl = ($DC1) . "" . ($DC2);   // los 2 números del D.C.
        //if (strlen($DigControl)==3) $DigControl=substr($DigControl,1,2);
        return $DigControl;
    }

}

?>
