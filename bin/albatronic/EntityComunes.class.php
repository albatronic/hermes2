<?php

/**
 * Description of EntityComunes
 *
 * Definicion de propiedades y métodos comunes a todas las entidades de datos
 *
 * @date 03-08-2012
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright (c) Informática ALBATRONIC, SL
 */
class EntityComunes extends Entity {

    /**
     * @orm Column(type="string")
     * @var text
     */
    protected $Observations;

    /**
     * @orm Column(type="string")
     * @var string(100)
     */
    protected $PrimaryKeyMD5;

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $IsDefault = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $IsSuper = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $Checked = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $Publish = '0';

    /**
     * @orm Column(type="int")
     * @var integer(11)
     */
    protected $BelongsTo = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $AllowsChildren = '0';

    /**
     * @orm Column(type="integer")
     * @var entities\Agentes
     */
    protected $CreatedBy = '0';

    /**
     * @orm Column(type="datetime")
     * @assert:NotBlank
     * @var datetime
     */
    protected $CreatedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="integer")
     * @var entities\Agentes
     */
    protected $ModifiedBy = '0';

    /**
     * @orm Column(type="datetime")
     * @assert:NotBlank
     * @var datetime
     */
    protected $ModifiedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $Deleted = '0';

    /**
     * @orm Column(type="integer")
     * @var entities\Agentes
     */
    protected $DeletedBy = '0';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $DeletedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="integer")
     * @var entities\Agentes
     */
    protected $PrintedBy = '0';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $PrintedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="integer")
     * @var entities\Agentes
     */
    protected $EmailedBy = '0';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $EmailedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresPrivacy
     */
    protected $Privacy = '2';

    /**
     * @orm Column(type="integer")
     * @var integer(11)
     */
    protected $SortOrder = '0';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $PublishedAt = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $ActiveFrom = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="datetime")
     * @var datetime
     */
    protected $ActiveTo = '0000-00-00 00:00:00';

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $UrlPrefix;

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $LockUrlPrefix = '1';

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $Slug;

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $LockSlug = '1';

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $UrlFriendly;

    /**
     * @orm Column(type="string")
     * @var entities\ValoresSN
     */
    protected $UrlHeritable = '0';

    /**
     * @orm Column(type="integer")
     * @var integer(11)
     */
    protected $NumberVisits = '0';

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $MetatagTitle;

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $LockMetatagTitle = '1';

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $MetatagKeywords;

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $MetatagDescription;

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $MetatagTitleSimple = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresDchaIzq
     */
    protected $MetatagTitlePosition = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowOnSitemap = '1';

    /**
     * @orm Column(type="string")
     * @var string(5)
     */
    protected $ImportanceSitemap = '0,5';

    /**
     * @orm Column(type="string")
     * @var entities\ValoresChangeFreq
     */
    protected $ChangeFreqSitemap = 'daily';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowGalery = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowDocuments = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowRelatedLinks = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowRelatedContents = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $ShowPublishedAt = '0';

    /**
     * @orm Column(type="string")
     * @var string(100)
     */
    protected $AccessProfileList = "perfiles:\n 1: '1'\n 2: '2'";

    /**
     * @orm Column(type="string")
     * @var string(100)
     */
    protected $AccessProfileListWeb = "perfiles:\n 1: '1'";

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $UrlTarget = NULL;

    /**
     * @orm Column(type="string")
     * @var string(255)
     */
    protected $UrlParameters = NULL;

    /**
     * @orm Column(type="tinyint")
     * @var entities\RequestMethods
     */
    protected $UrlRequestMethod = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\RequestOrigins
     */
    protected $UrlOrigin = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $UrlTargetBlank = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\ValoresSN
     */
    protected $UrlIsHttps = '0';

    /**
     * @orm Column(type="tinyint")
     * @var entities\CpanAplicaciones
     */
    protected $CodigoAppAsociada = NULL;
    protected $IdAlbumExterno = NULL;
    protected $IdSliderAsociado = NULL;
    protected $IdSeccionEnlaces = NULL;
    protected $IdSeccionVideos = NULL;
    protected $RevisitAfter = '10';
    protected $NivelJerarquico = 1;

    /**
     * Fecha y hora última visita en formato UNIX
     * @var integer
     */
    protected $DateTimeLastVisit = '0';

    /**
     * METODOS ELABORADOS
     */

    /**
     * Devuelve el objeto CpanUrlAmigables asociado
     */
    public function getObjetoUrlAmigable() {

        $url = new CpanUrlAmigables();
        $rows = $url->cargaCondicion("Id", "Idioma='{$_SESSION['idiomas']['actual']}' and Entity='{$this->getClassName()}' and IdEntity='{$this->getPrimaryKeyValue()}'");
        unset($url);

        return new CpanUrlAmigables($rows[0]['Id']);
    }

    /**
     * Devuelve un array con los elemementos necesarios para
     * construir un <a href=''> 
     * 
     * Tiene dos elmentos:
     * 
     *  'url'   Es la url en si con el prefijo, que puede ser: nada, http, o https)
     *  'targetBlank'   Es un flag booleano para saber si el enlace se abrirá en popup o no
     * 
     * @return array Array
     */
    public function getHref() {

        $url = $this->getUrlTarget();
        $esInterna = ($url == '');

        if ($esInterna) {
            $url = $this->getUrlFriendly();
            $prefijo = $_SESSION['appPath'];
        } else {
            $prefijo = ($this->UrlIsHttps) ? "https://" : "http://";
        }

        $url = $prefijo . $url . $this->getUrlParameters();

        $array = array('url' => $url, 'targetBlank' => $this->UrlTargetBlank);


        return $array;
    }

    /**
     * GETERS Y SETERS
     */
    public function setObservations($Observations) {
        $this->Observations = trim($Observations);
    }

    public function getObservations() {
        return $this->Observations;
    }

    public function setPrimaryKeyMD5($PrimaryKeyMD5) {
        $this->PrimaryKeyMD5 = trim($PrimaryKeyMD5);
    }

    public function getPrimaryKeyMD5() {
        return $this->PrimaryKeyMD5;
    }

    public function setIsDefault($IsDefault) {
        $this->IsDefault = $IsDefault;
    }

    public function getIsDefault() {
        if (!($this->IsDefault instanceof ValoresSN))
            $this->IsDefault = new ValoresSN($this->IsDefault);
        return $this->IsDefault;
    }

    public function setIsSuper($IsSuper) {
        $this->IsSuper = $IsSuper;
    }

    public function getIsSuper() {
        if (!($this->IsSuper instanceof ValoresSN))
            $this->IsSuper = new ValoresSN($this->IsSuper);
        return $this->IsSuper;
    }

    public function setChecked($Checked) {
        $this->Checked = $Checked;
    }

    public function getChecked() {
        if (!($this->Checked instanceof ValoresSN))
            $this->Checked = new ValoresSN($this->Checked);
        return $this->Checked;
    }

    public function setPublish($Publish) {
        $this->Publish = $Publish;
    }

    public function getPublish() {
        if (!($this->Publish instanceof ValoresSN))
            $this->Publish = new ValoresSN($this->Publish);
        return $this->Publish;
    }

    public function setBelongsTo($BelongsTo) {
        $this->BelongsTo = $BelongsTo;
    }

    public function getBelongsTo() {

        if (!is_object($this->BelongsTo)) {
            $clase = $this->getClassName();
            $this->BelongsTo = new $clase($this->BelongsTo);
        }

        return $this->BelongsTo;
    }

    public function setAllowsChildren($AllowsChildren) {
        $this->AllowsChildren = $AllowsChildren;
    }

    public function getAllowsChildren() {
        if (!($this->AllowsChildren instanceof ValoresSN))
            $this->AllowsChildren = new ValoresSN($this->AllowsChildren);
        return $this->AllowsChildren;
    }

    public function setCreatedBy($CreateBy) {
        $this->CreatedBy = $CreateBy;
    }

    public function getCreatedBy() {
        if (!($this->CreatedBy instanceof Agentes))
            $this->CreatedBy = new Agentes($this->CreatedBy);

        return $this->CreatedBy;
    }

    public function setCreatedAt($CreatedAt) {
        $this->CreatedAt = $CreatedAt;
    }

    public function getCreatedAt() {
        return date_format(date_create($this->CreatedAt), 'd-m-Y H:i:s');
    }

    public function setModifiedBy($ModifiedBy) {
        $this->ModifiedBy = $ModifiedBy;
    }

    public function getModifiedBy() {
        if (!($this->ModifiedBy instanceof Agentes))
            $this->ModifiedBy = new Agentes($this->ModifiedBy);
        return $this->ModifiedBy;
    }

    public function setModifiedAt($ModifiedAt) {
        $this->ModifiedAt = $ModifiedAt;
    }

    public function getModifiedAt() {
        return date_format(date_create($this->ModifiedAt), 'd-m-Y H:i:s');
    }

    public function setDeleted($Deleted) {
        $this->Deleted = $Deleted;
    }

    public function getDeleted() {
        if (!($this->Deleted instanceof ValoresSN))
            $this->Deleted = new ValoresSN($this->Deleted);
        return $this->Deleted;
    }

    public function setDeletedBy($DeletedBy) {
        $this->DeletedBy = $DeletedBy;
    }

    public function getDeletedBy() {
        if (!($this->DeletedBy instanceof Agentes))
            $this->DeletedBy = new Agentes($this->DeletedBy);

        return $this->DeletedBy;
    }

    public function setDeletedAt($DeletedAt) {
        $this->DeletedAt = $DeletedAt;
    }

    public function getDeletedAt() {
        return date_format(date_create($this->DeletedAt), 'd-m-Y H:i:s');
    }

    public function setPrintedBy($PrintedBy) {
        $this->PrintedBy = $PrintedBy;
    }

    public function getPrintedBy() {
        if (!($this->PrintedBy instanceof Agentes))
            $this->PrintedBy = new Agentes($this->PrintedBy);
        return $this->PrintedBy;
    }

    public function setPrintedAt($PrintedAt) {
        $this->PrintedAt = $PrintedAt;
    }

    public function getPrintedAt() {
        return date_format(date_create($this->PrintedAt), 'd-m-Y H:i:s');
    }

    public function setEmailedBy($EmailedBy) {
        $this->EmailedBy = $EmailedBy;
    }

    public function getEmailedBy() {
        if (!($this->EmailedBy instanceof Agentes))
            $this->EmailedBy = new Agentes($this->EmailedBy);
        return $this->EmailedBy;
    }

    public function setEmailedAt($EmailedAt) {
        $this->EmailedAt = $EmailedAt;
    }

    public function getEmailedAt() {
        return date_format(date_create($this->EmailedAt), 'd-m-Y H:i:s');
    }

    public function setPrivacy($Privacy) {
        $this->Privacy = $Privacy;
    }

    public function getPrivacy() {
        if (!($this->Privacy instanceof ValoresPrivacy))
            $this->Privacy = new ValoresPrivacy($this->Privacy);
        return $this->Privacy;
    }

    public function setSortOrder($SortOrder) {
        $this->SortOrder = $SortOrder;
    }

    public function getSortOrder() {
        return $this->SortOrder;
    }

    public function setPublishedAt($PublishedAt) {
        if ($PublishedAt != '0000-00-00 00:00:00') {
            $date = new Fecha($PublishedAt, true);
            $this->PublishedAt = $date->getFechaTime();
        } else {
            $this->PublishedAt = date('Y-m-d H:i:s');
        }
        unset($date);
    }

    public function getPublishedAt() {
        return date_format(date_create($this->PublishedAt), 'd-m-Y H:i:s');
    }

    public function setActiveFrom($ActiveFrom) {
        if ($ActiveFrom == '0000-00-00 00:00:00')
            $ActiveFrom = $_SESSION['VARIABLES']['EnvPro']['activeFrom'];

        if ($ActiveFrom == '')
            $ActiveFrom = '0000-00-00 00:00:00';

        $date = new Fecha($ActiveFrom, true);
        $this->ActiveFrom = $date->getFechaTime();
        unset($date);
    }

    public function getActiveFrom($formato = "ddmmaaaahhmmss") {

        if ($this->ActiveFrom > 0) {
            switch ($formato) {
                case 'ddmmaaaahhmmss':
                    $valor = date_format(date_create($this->ActiveFrom), 'd-m-Y H:i:s');
                    break;
                case 'aaaammdd':
                    $valor = date_format(date_create($this->ActiveFrom), 'Y-m-d');
            }
        } else {
            $valor = "00-00-0000 00:00:00";
        }

        return $valor;
    }

    public function getActiveFromXXX() {
        return ($this->ActiveFrom > 0) ? date_format(date_create($this->ActiveFrom), 'd-m-Y H:i:s') : '00-00-0000 00:00:00';
    }

    public function setActiveTo($ActiveTo) {
        if ($ActiveTo == '0000-00-00 00:00:00')
            $ActiveTo = $_SESSION['VARIABLES']['EnvPro']['activeTo'];

        if ($ActiveTo == '')
            $ActiveTo = '0000-00-00 00:00:00';

        $date = new Fecha($ActiveTo, true);
        $this->ActiveTo = $date->getFechaTime();
        unset($date);
    }

    public function getActiveTo($formato = "ddmmaaaahhmmss") {

        if ($this->ActiveTo > 0) {
            switch ($formato) {
                case 'ddmmaaaahhmmss':
                    $valor = date_format(date_create($this->ActiveTo), 'd-m-Y H:i:s');
                    break;
                case 'aaaammdd':
                    $valor = date_format(date_create($this->ActiveTo), 'Y-m-d');
            }
        } else {
            $valor = "00-00-0000 00:00:00";
        }

        return $valor;
    }

    public function getActiveToXXX() {
        return ($this->ActiveTo > 0) ? date_format(date_create($this->ActiveTo), 'd-m-Y H:i:s') : '00-00-0000 00:00:00';
    }

    public function setUrlPrefix($UrlPrefix) {
        $this->UrlPrefix = trim($UrlPrefix);
    }

    public function getUrlPrefix() {
        return $this->UrlPrefix;
    }

    public function setLockUrlPrefix($LockUrlPrefix) {
        $this->LockUrlPrefix = $LockUrlPrefix;
    }

    public function getLockUrlPrefix() {
        if (!($this->LockUrlPrefix instanceof ValoresSN))
            $this->LockUrlPrefix = new ValoresSN($this->LockUrlPrefix);
        return $this->LockUrlPrefix;
    }

    public function setSlug($Slug) {
        $this->Slug = trim($Slug);
    }

    public function getSlug() {
        return $this->Slug;
    }

    public function setLockSlug($LockSlug) {
        $this->LockSlug = $LockSlug;
    }

    public function getLockSlug() {
        if (!($this->LockSlug instanceof ValoresSN))
            $this->LockSlug = new ValoresSN($this->LockSlug);
        return $this->LockSlug;
    }

    public function setUrlFriendly($UrlFriendly) {
        $this->UrlFriendly = trim($UrlFriendly);
    }

    public function getUrlFriendly() {
        return $this->UrlFriendly;
    }

    public function setUrlHeritable($UrlHeritable) {
        $this->UrlHeritable = $UrlHeritable;
    }

    public function getUrlHeritable() {
        if (!($this->UrlHeritable instanceof ValoresSN))
            $this->UrlHeritable = new ValoresSN($this->UrlHeritable);
        return $this->UrlHeritable;
    }

    public function setNumberVisits($NumberVisits) {
        $this->NumberVisits = $NumberVisits;
    }

    public function getNumberVisits() {
        return $this->NumberVisits;
    }

    public function setMetatagTitle($MetatagTitle) {
        $this->MetatagTitle = trim($MetatagTitle);
    }

    public function getMetatagTitle() {
        return $this->MetatagTitle;
    }

    public function setLockMetatagTitle($LockMetatagTitle) {
        $this->LockMetatagTitle = $LockMetatagTitle;
    }

    public function getLockMetatagTitle() {
        if (!($this->LockMetatagTitle instanceof ValoresSN))
            $this->LockMetatagTitle = new ValoresSN($this->LockMetatagTitle);
        return $this->LockMetatagTitle;
    }

    public function setMetatagKeywords($MetatagKeywords) {
        $this->MetatagKeywords = trim($MetatagKeywords);
    }

    public function getMetatagKeywords() {
        return $this->MetatagKeywords;
    }

    public function setMetatagDescription($MetatagDescription) {
        $this->MetatagDescription = trim($MetatagDescription);
    }

    public function getMetatagDescription() {
        return $this->MetatagDescription;
    }

    public function setMetatagTitleSimple($MetatagTitleSimple) {
        $this->MetatagTitleSimple = $MetatagTitleSimple;
    }

    public function getMetatagTitleSimple() {
        if (!($this->MetatagTitleSimple instanceof ValoresSN))
            $this->MetatagTitleSimple = new ValoresSN($this->MetatagTitleSimple);
        return $this->MetatagTitleSimple;
    }

    public function setMetatagTitlePosition($MetatagTitlePosition) {
        $this->MetatagTitlePosition = $MetatagTitlePosition;
    }

    public function getMetatagTitlePosition() {
        if (!($this->MetatagTitlePosition instanceof ValoresDchaIzq))
            $this->MetatagTitlePosition = new ValoresDchaIzq($this->MetatagTitlePosition);
        return $this->MetatagTitlePosition;
    }

    public function setShowOnSitemap($ShowOnSitemap) {
        $this->ShowOnSitemap = $ShowOnSitemap;
    }

    public function getShowOnSitemap() {
        if (!($this->ShowOnSitemap instanceof ValoresSN))
            $this->ShowOnSitemap = new ValoresSN($this->ShowOnSitemap);
        return $this->ShowOnSitemap;
    }

    public function setImportanceSitemap($ImportanceSitemap) {
        $this->ImportanceSitemap = trim($ImportanceSitemap);
    }

    public function getImportanceSitemap() {
        return $this->ImportanceSitemap;
    }

    public function setChangeFreqSitemap($ChangeFreqSitemap) {
        $this->ChangeFreqSitemap = $ChangeFreqSitemap;
    }

    public function getChangeFreqSitemap() {
        if (!($this->ChangeFreqSitemap instanceof ValoresChangeFreq))
            $this->ChangeFreqSitemap = new ValoresChangeFreq($this->ChangeFreqSitemap);
        return $this->ChangeFreqSitemap;
    }

    public function setShowGalery($ShowGalery) {
        $this->ShowGalery = $ShowGalery;
    }

    public function getShowGalery() {
        if (!($this->ShowGalery instanceof ValoresSN))
            $this->ShowGalery = new ValoresSN($this->ShowGalery);
        return $this->ShowGalery;
    }

    public function setShowDocuments($ShowDocuments) {
        $this->ShowDocuments = $ShowDocuments;
    }

    public function getShowDocuments() {
        if (!($this->ShowDocuments instanceof ValoresSN))
            $this->ShowDocuments = new ValoresSN($this->ShowDocuments);
        return $this->ShowDocuments;
    }

    public function setShowRelatedLinks($ShowRelatedLinks) {
        $this->ShowRelatedLinks = $ShowRelatedLinks;
    }

    public function getShowRelatedLinks() {
        if (!($this->ShowRelatedLinks instanceof ValoresSN))
            $this->ShowRelatedLinks = new ValoresSN($this->ShowRelatedLinks);
        return $this->ShowRelatedLinks;
    }

    public function setShowRelatedContents($ShowRelatedContents) {
        $this->ShowRelatedContents = $ShowRelatedContents;
    }

    public function getShowRelatedContents() {
        if (!($this->ShowRelatedContents instanceof ValoresSN))
            $this->ShowRelatedContents = new ValoresSN($this->ShowRelatedContents);
        return $this->ShowRelatedContents;
    }

    public function setShowPublishedAt($ShowPublishedAt) {
        $this->ShowPublishedAt = $ShowPublishedAt;
    }

    public function getShowPublishedAt() {
        if (!($this->ShowPublishedAt instanceof ValoresSN))
            $this->ShowPublishedAt = new ValoresSN($this->ShowPublishedAt);
        return $this->ShowPublishedAt;
    }

    public function setAccessProfileList($AccessProfileList) {
        $this->AccessProfileList = (is_array($AccessProfileList)) ?
                sfYaml::dump($AccessProfileList) :
                trim($AccessProfileList);
    }

    public function getAccessProfileList() {
        return sfYaml::load($this->AccessProfileList);
    }

    public function setAccessProfileListWeb($AccessProfileListWeb) {
        $this->AccessProfileListWeb = (is_array($AccessProfileListWeb)) ?
                sfYaml::dump($AccessProfileListWeb) :
                trim($AccessProfileListWeb);
    }

    public function getAccessProfileListWeb() {
        return sfYaml::load($this->AccessProfileListWeb);
    }

    public function setUrlTarget($UrlTarget) {
        $this->UrlTarget = trim($UrlTarget);
    }

    public function getUrlTarget() {
        return $this->UrlTarget;
    }

    public function setUrlParameters($UrlParameters) {
        $this->UrlParameters = trim($UrlParameters);
    }

    public function getUrlParameters() {
        return $this->UrlParameters;
    }

    public function setUrlRequestMethod($UrlRequestMethod) {
        $this->UrlRequestMethod = $UrlRequestMethod;
    }

    public function getUrlRequestMethod() {
        if (!($this->UrlRequestMethod instanceof RequestMethods))
            $this->UrlRequestMethod = new RequestMethods($this->UrlRequestMethod);
        return $this->UrlRequestMethod;
    }

    public function setUrlOrigin($UrlOrigin) {
        $this->UrlOrigin = $UrlOrigin;
    }

    public function getUrlOrigin() {
        if (!($this->UrlOrigin instanceof RequestOrigins))
            $this->UrlOrigin = new RequestOrigins($this->UrlOrigin);
        return $this->UrlOrigin;
    }

    public function setUrlTargetBlank($UrlTargetBlank) {
        $this->UrlTargetBlank = $UrlTargetBlank;
    }

    public function getUrlTargetBlank() {
        if (!($this->UrlTargetBlank instanceof ValoresSN))
            $this->UrlTargetBlank = new ValoresSN($this->UrlTargetBlank);
        return $this->UrlTargetBlank;
    }

    public function setUrlIsHttps($UrlIsHttps) {
        $this->UrlIsHttps = $UrlIsHttps;
    }

    public function getUrlIsHttps() {
        if (!($this->UrlIsHttps instanceof ValoresSN))
            $this->UrlIsHttps = new ValoresSN($this->UrlIsHttps);
        return $this->UrlIsHttps;
    }

    public function setCodigoAppAsociada($CodigoAppAsociada) {
        $this->CodigoAppAsociada = $CodigoAppAsociada;
    }

    public function getCodigoAppAsociada() {
        if (!($this->CodigoAppAsociada instanceof CpanAplicaciones)) {
            $app = new CpanAplicaciones();
            $this->CodigoAppAsociada = $app->find('Id', $this->CodigoAppAsociada);
        }
        return $this->CodigoAppAsociada;
    }

    public function setIdAlbumExterno($IdAlbumExterno) {
        $this->IdAlbumExterno = $IdAlbumExterno;
    }

    public function getIdAlbumExterno() {
        if (!($this->IdAlbumExterno instanceof AlbmAlbumes)) {
            $this->IdAlbumExterno = new AlbmAlbumes($this->IdAlbumExterno);
        }

        return $this->IdAlbumExterno;
    }

    public function setIdSliderAsociado($IdSliderAsociado) {
        $this->IdSliderAsociado = $IdSliderAsociado;
    }

    public function getIdSliderAsociado() {
        if (!($this->IdSliderAsociado instanceof SldSliders)) {
            $this->IdSliderAsociado = new SldSliders($this->IdSliderAsociado);
        }

        return $this->IdSliderAsociado;
    }

    public function setIDSeccionEnlaces($IDSeccionEnlaces) {
        $this->IdSeccionEnlaces = $IDSeccionEnlaces;
    }

    public function getIDSeccionEnlaces() {
        if (!($this->IdSeccionEnlaces instanceof EnlSecciones)) {
            $this->IdSeccionEnlaces = new EnlSecciones($this->IdSeccionEnlaces);
        }

        return $this->IdSeccionEnlaces;
    }

    public function setIDSeccionVideos($IDSeccionVideos) {
        $this->IdSeccionVideos = $IDSeccionVideos;
    }

    public function getIDSeccionVideos() {
        if (!($this->IdSeccionVideos instanceof VidSecciones)) {
            $this->IdSeccionVideos = new VidSecciones($this->IdSeccionVideos);
        }

        return $this->IdSeccionVideos;
    }

    public function setDateTimeLastVisit($TimeUnix = 0) {

        if ($TimeUnix == 0)
            $TimeUnix = time();

        $this->DateTimeLastVisit = $TimeUnix;
    }

    public function getDateTimeLastVisit() {
        return date('d-m-Y H:i:s', $this->DateTimeLastVisit);
    }

    public function setRevisitAfter($RevisitAfter) {
        $this->RevisitAfter = trim($RevisitAfter);
    }

    public function getRevisitAfter() {
        return $this->RevisitAfter;
    }

    public function setNivelJerarquico($NivelJerarquico) {
        $this->NivelJerarquico = $NivelJerarquico;
    }

    public function getNivelJerarquico() {
        return $this->NivelJerarquico;
    }

    public function getArrayPerfilesCpanel() {
        // Cargar los perfiles del cpanel
        $perfiles = new CpanPerfiles();
        $cpanPerfiles = $perfiles->fetchAll('Perfil', false);
        unset($perfiles);

        $objetoPerfiles = $this->getAccessProfileList();

        if (is_array($objetoPerfiles)) {
            foreach ($cpanPerfiles as $key => $perfil) {
                if (is_array($objetoPerfiles['perfiles'])) {
                    $cpanPerfiles[$key]['Valor'] = array_search($perfil['Id'], $objetoPerfiles['perfiles']);
                }
            }
        }

        return $cpanPerfiles;
    }

    public function getArrayPerfilesWeb() {
        // Cargar los perfiles de la web
        $perfiles = new WebPerfiles();
        $webPerfiles = $perfiles->fetchAll('Perfil', false);
        unset($perfiles);

        $objetoPerfiles = $this->getAccessProfileListWeb();

        if (is_array($objetoPerfiles)) {
            foreach ($webPerfiles as $key => $perfil) {
                $webPerfiles[$key]['Valor'] = array_search($perfil['Id'], $objetoPerfiles['perfiles']);
            }
        }

        return $webPerfiles;
    }

}

?>
