<?php

/**
 * Clientes
 * 
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright INFORMATICA ALBATRONIC SL
 * @since 12.06.2011 13:34:41
 */

/**
 * @orm:Entity(clientes)
 */
class ClientesEntity extends EntityComunes {

    /**
     * @orm:GeneratedValue
     * @orm:Id
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDCliente;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $RazonSocial = '';

    /**
     * @orm:Column(type="string")
     */
    protected $NombreComercial;

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Cif;

    /**
     * @orm:Column(type="string")
     */
    protected $Direccion;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDPais = '68';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDProvincia = '18';

    /**
     * @orm:Column(type="string")
     */
    protected $IDPoblacion = '0';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $CodigoPostal = '0000000000';

    /**
     * @orm:Column(type="string")
     */
    protected $Telefono;

    /**
     * @orm:Column(type="string")
     */
    protected $Fax;

    /**
     * @orm:Column(type="string")
     */
    protected $Movil;

    /**
     * @orm:Column(type="string")
     */
    protected $EMail;

    /**
     * @orm:Column(type="string")
     */
    protected $Web;

    /**
     * @orm:Column(type="string")
     */
    protected $CContable = '0000000000';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Banco = '0000';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Oficina = '0000';

    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Digito = '00';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Cuenta = '0000000000';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDTipo = '0';

    /**
     * @orm:Column(type="integer")
     */
    protected $IDGrupo = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDFP = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $DiaDePago = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $RecargoEqu = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Sexo = '1';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Tratamiento = '1';

    /**
     * @orm:Column(type="string")
     */
    protected $Observaciones = '';

    /**
     * @orm:Column(type="string")
     */
    protected $Avisos = '';

    /**
     * @orm:Column(type="")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Vigente = '1';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDComercial = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDTarifa = '0';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $LimiteRiesgo = '0.00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $FechaNacimiento = '0000-00-00';

    /**
     * @orm:Column(type="string")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Login = null;

    /**
     * @orm:Column(type="string")
     */
    protected $Password;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $Iva = '1';

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="clientes")
     */
    protected $FechaRiesgo = '0000-00-00';

    /**
     * @orm:Column(type="string")
     */
    protected $CodigoRiesgo;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDSucursal = '1';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDZona;

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $FacturacionAgrupada = '1';

    /**
     * @orm:Column(type="string")
     */
    protected $Iban = null;

    /**
     * @orm:Column(type="string")
     */
    protected $Bic = null;

    /**
     * @orm:Column(type="string")
     */
    protected $Mandato;

    /**
     * @orm:Column(type="date")
     * @assert:NotBlank(groups="clientes")
     */
    protected $FechaMandato = '0000-00-00';

    /**
     * @orm:Column(type="integer")
     * @assert:NotBlank(groups="clientes")
     */
    protected $IDPerfilWeb = '1';

    /**
     * Nombre de la conexion a la DB
     * @var string
     */
    protected $_conectionName = '';

    /**
     * Nombre de la tabla física
     * @var string
     */
    protected $_tableName = 'ErpClientes';

    /**
     * Nombre de la primaryKey
     * @var string
     */
    protected $_primaryKeyName = 'IDCliente';

    /**
     * Relacion de entidades que dependen de esta
     * @var array
     */
    protected $_parentEntities = array(
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'AlbaranesCab', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'PstoCab', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'FemitidasCab', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'PromocionesClientes', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'ClientesContactos', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'ClientesDentrega', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'RecibosClientes', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'RutasVentas', 'ParentColumn' => 'IDCliente'),
        array('SourceColumn' => 'IDCliente', 'ParentEntity' => 'Articulos', 'ParentColumn' => 'IDCliente'),
    );

    /**
     * Relacion de entidades de las que depende esta
     * @var array
     */
    protected $_childEntities = array(
    );

    /**
     * GETTERS Y SETTERS
     */
    public function setIDCliente($IDCliente) {
        $this->IDCliente = $IDCliente;
    }

    public function getIDCliente() {
        return $this->IDCliente;
    }

    public function setRazonSocial($RazonSocial) {
        $this->RazonSocial = trim($RazonSocial);
    }

    public function getRazonSocial() {
        return $this->RazonSocial;
    }

    public function setNombreComercial($NombreComercial) {
        $this->NombreComercial = trim($NombreComercial);
    }

    public function getNombreComercial() {
        return $this->NombreComercial;
    }

    public function setCif($Cif) {
        $this->Cif = $Cif;
    }

    public function getCif() {
        return $this->Cif;
    }

    public function setDireccion($Direccion) {
        $this->Direccion = trim($Direccion);
    }

    public function getDireccion() {
        return $this->Direccion;
    }

    public function setIDPais($IDPais) {
        $this->IDPais = $IDPais;
    }

    public function getIDPais() {
        if (!($this->IDPais instanceof Paises))
            $this->IDPais = new Paises($this->IDPais);
        return $this->IDPais;
    }

    public function setIDPoblacion($IDPoblacion) {
        $this->IDPoblacion = $IDPoblacion;
    }

    public function getIDPoblacion() {
        if (!($this->IDPoblacion instanceof Municipios))
            $this->IDPoblacion = new Municipios($this->IDPoblacion);
        return $this->IDPoblacion;
    }

    public function setIDProvincia($IDProvincia) {
        $this->IDProvincia = $IDProvincia;
    }

    public function getIDProvincia() {
        if (!($this->IDProvincia instanceof Provincias))
            $this->IDProvincia = new Provincias($this->IDProvincia);
        return $this->IDProvincia;
    }

    public function setCodigoPostal($CodigoPostal) {
        $this->CodigoPostal = $CodigoPostal;
    }

    public function getCodigoPostal() {
        return $this->CodigoPostal;
    }

    public function setTelefono($Telefono) {
        $this->Telefono = trim($Telefono);
    }

    public function getTelefono() {
        return $this->Telefono;
    }

    public function setFax($Fax) {
        $this->Fax = trim($Fax);
    }

    public function getFax() {
        return $this->Fax;
    }

    public function setMovil($Movil) {
        $this->Movil = trim($Movil);
    }

    public function getMovil() {
        return $this->Movil;
    }

    public function setEMail($EMail) {
        $this->EMail = trim($EMail);
    }

    public function getEMail() {
        return $this->EMail;
    }

    public function setWeb($Web) {
        $this->Web = trim($Web);
    }

    public function getWeb() {
        return $this->Web;
    }

    public function setCContable($CContable) {
        $this->CContable = trim($CContable);
    }

    public function getCContable() {
        return $this->CContable;
    }

    public function setBanco($Banco) {
        $this->Banco = str_pad(trim($Banco), 4, "0");
    }

    public function getBanco() {
        return $this->Banco;
    }

    public function setOficina($Oficina) {
        $this->Oficina = str_pad(trim($Oficina), 4, "0");
    }

    public function getOficina() {
        return $this->Oficina;
    }

    public function setDigito($Digito) {
        $this->Digito = str_pad(trim($Digito), 2, "0");
    }

    public function getDigito() {
        return $this->Digito;
    }

    public function setCuenta($Cuenta) {
        $this->Cuenta = str_pad(trim($Cuenta), 10, "0");
    }

    public function getCuenta() {
        return $this->Cuenta;
    }

    public function setIDTipo($IDTipo) {
        $this->IDTipo = $IDTipo;
    }

    public function getIDTipo() {
        if (!($this->IDTipo instanceof ClientesTipos))
            $this->IDTipo = new ClientesTipos($this->IDTipo);
        return $this->IDTipo;
    }

    public function setIDGrupo($IDGrupo) {
        $this->IDGrupo = $IDGrupo;
    }

    public function getIDGrupo() {
        if (!($this->IDGrupo instanceof ClientesGrupos))
            $this->IDGrupo = new ClientesGrupos($this->IDGrupo);
        return $this->IDGrupo;
    }

    public function setIDFP($IDFP) {
        $this->IDFP = $IDFP;
    }

    public function getIDFP() {
        if (!($this->IDFP instanceof FormasPago))
            $this->IDFP = new FormasPago($this->IDFP);
        return $this->IDFP;
    }

    public function setDiaDePago($DiaDePago) {
        $this->DiaDePago = $DiaDePago;
    }

    public function getDiaDePago() {
        return $this->DiaDePago;
    }

    public function setRecargoEqu($RecargoEqu) {
        $this->RecargoEqu = $RecargoEqu;
    }

    public function getRecargoEqu() {
        if (!($this->RecargoEqu instanceof ValoresSN))
            $this->RecargoEqu = new ValoresSN($this->RecargoEqu);
        return $this->RecargoEqu;
    }

    public function setSexo($Sexo) {
        $this->Sexo = $Sexo;
    }

    public function getSexo() {
        if (!($this->Sexo instanceof Sexos))
            $this->Sexo = new Sexos($this->Sexo);
        return $this->Sexo;
    }

    public function setTratamiento($Tratamiento) {
        $this->Tratamiento = $Tratamiento;
    }

    public function getTratamiento() {
        if (!($this->Tratamiento instanceof Tratamientos))
            $this->Tratamiento = new Tratamientos($this->Tratamiento);
        return $this->Tratamiento;
    }

    public function setObservaciones($Observaciones) {
        $this->Observaciones = trim($Observaciones);
    }

    public function getObservaciones() {
        return $this->Observaciones;
    }

    public function setAvisos($Avisos) {
        $this->Avisos = trim($Avisos);
    }

    public function getAvisos() {
        return $this->Avisos;
    }

    public function setVigente($Vigente) {
        $this->Vigente = $Vigente;
    }

    public function getVigente() {
        if (!($this->Vigente instanceof ValoresSN))
            $this->Vigente = new ValoresSN($this->Vigente);
        return $this->Vigente;
    }

    public function setIDComercial($IDComercial) {
        $this->IDComercial = $IDComercial;
    }

    public function getIDComercial() {
        if (!($this->IDComercial instanceof Agentes))
            $this->IDComercial = new Agentes($this->IDComercial);
        return $this->IDComercial;
    }

    public function setIDTarifa($IDTarifa) {
        $this->IDTarifa = $IDTarifa;
    }

    public function getIDTarifa() {
        if (!($this->IDTarifa instanceof Tarifas))
            $this->IDTarifa = new Tarifas($this->IDTarifa);
        return $this->IDTarifa;
    }

    public function setLimiteRiesgo($LimiteRiesgo) {
        $this->LimiteRiesgo = $LimiteRiesgo;
    }

    public function getLimiteRiesgo() {
        return $this->LimiteRiesgo;
    }

    public function setFechaNacimiento($FechaNacimiento) {
        $fecha = new Fecha($FechaNacimiento);
        $this->FechaNacimiento = $fecha->getFecha();
        unset($fecha);
    }

    public function getFechaNacimiento() {
        $fecha = new Fecha($this->FechaNacimiento);
        $ddmmaaaa = $fecha->getddmmaaaa();
        unset($fecha);
        return $ddmmaaaa;
    }

    public function setLogin($Login) {
        $this->Login = trim($Login);
    }

    public function getLogin() {
        return $this->Login;
    }

    public function setPassword($Password) {
        $this->Password = trim($Password);
    }

    public function getPassword() {
        return $this->Password;
    }

    public function setIva($Iva) {
        $this->Iva = $Iva;
    }

    public function getIva() {
        if (!($this->Iva instanceof ValoresSN))
            $this->Iva = new ValoresSN($this->Iva);
        return $this->Iva;
    }

    public function setFechaRiesgo($FechaRiesgo) {
        $fecha = new Fecha($FechaRiesgo);
        $this->FechaRiesgo = $fecha->getFecha();
        unset($fecha);
    }

    public function getFechaRiesgo() {
        $fecha = new Fecha($this->FechaRiesgo);
        $ddmmaaaa = $fecha->getddmmaaaa();
        unset($fecha);
        return $ddmmaaaa;
    }

    public function setIDSucursal($IDSucursal) {
        $this->IDSucursal = $IDSucursal;
    }

    public function getIDSucursal() {
        if (!($this->IDSucursal instanceof Sucursales))
            $this->IDSucursal = new Sucursales($this->IDSucursal);
        return $this->IDSucursal;
    }

    public function setIDZona($IDZona) {
        $this->IDZona = $IDZona;
    }

    public function getIDZona() {
        if (!($this->IDZona instanceof Zonas))
            $this->IDZona = new Zonas($this->IDZona);
        return $this->IDZona;
    }

    public function setCodigoRiesgo($CodigoRiesgo) {
        $this->CodigoRiesgo = $CodigoRiesgo;
    }

    public function getCodigoRiesgo() {
        return $this->CodigoRiesgo;
    }

    public function setFacturacionAgrupada($FacturacionAgrupada) {
        $this->FacturacionAgrupada = $FacturacionAgrupada;
    }

    public function getFacturacionAgrupada() {
        if (!($this->FacturacionAgrupada instanceof ValoresSN))
            $this->FacturacionAgrupada = new ValoresSN($this->FacturacionAgrupada);
        return $this->FacturacionAgrupada;
    }

    public function setIban($Iban) {
        $this->Iban = trim($Iban);
    }

    public function getIban() {
        return $this->Iban;
    }

    public function setBic($Bic) {
        $this->Bic = trim($Bic);
    }

    public function getBic() {
        return $this->Bic;
    }

    public function setMandato($Mandato) {
        $this->Mandato = trim($Mandato);
    }

    public function getMandato() {
        return $this->Mandato;
    }

    public function setFechaMandato($FechaMandato) {
        $fecha = new Fecha($FechaMandato);
        $this->FechaMandato = $fecha->getFecha();
        unset($fecha);
    }

    public function getFechaMandato() {
        $fecha = new Fecha($this->FechaMandato);
        $ddmmaaaa = $fecha->getddmmaaaa();
        unset($fecha);
        return $ddmmaaaa;
    }

    public function setIDPerfilWeb($IDPerfilWeb) {
        $this->IDPerfilWeb = $IDPerfilWeb;
    }

    public function getIDPerfilWeb() {
        if (!($this->IDPerfilWeb instanceof WebPerfiles))
            $this->IDPerfilWeb = new WebPerfiles($this->IDPerfilWeb);
        return $this->IDPerfilWeb;
    }
}

// END class clientes
?>