/*
 * Debe estar definida la variable appPath que indica el path a la app
 * Se le asigna valor en 'modules/_global/layoutStd.html.twig' y 'modules/_global/layoutPopup.html.twig'
 *
 * @author Sergio Perez <sergio.perez@albatronic.com>
 * @copyright Informatica Albatronic, sl
 * @since 22/01/2012
 */

$(function(){
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
    
    /**
     * Para las solapas
     */
    $( "#tabs" ).tabs({active: parseInt($('#solapaActiva').val(),10)});
    $('#tabs ul li').click(function(){
        $('#solapaActiva').val( $("#tabs").tabs("option","active") );
    });     
    $( "#tabs1" ).tabs(); 
    $( "#tabsMostrarEnMenu" ).tabs();
    
    /**
     * Diálogo para el filtro avanzado
     */
    $( "#filtroAvanzado" ).dialog({
        autoOpen: false,
        width: 340,
        //height: 420,
        position: ['right','center'],
        closeOnEscape: true,
        show: 'slide',
        resizable: false,
        buttons: {
            "Consultar" : function() {
                $('#div_listado').html('<div class=ListadoAnimation><img src={{app.path}}/images/loadingAnimation.gif /></div>');
                $('#formFiltroAvanzado').submit();
                },
            "Cancelar" : function(){
                $(this).dialog('close');
            }

        }            
    });
        
    /**
     * Diálogo popup variables de entorno
     */
    $( "#filtroAvanzado1" ).dialog({
        autoOpen: false,
        modal: false,
        width: 350,
        height: 540,
        position: ['right','center'],
        closeOnEscape: true,
        buttons: {
            "Consultar" : function() {
                // Enviar el formulario por ajax
                var $formulario = $('#filtro');
                $(formulario).submit(function(e){
                    var valores = $formulario.serialize();
                    alert(valores);
                    var $envio = $.ajax({
                        url: appPath + '/GconContenidos/list',
                        data: valores,
                        type: 'POST'
                    });
                    
                    $envio.done(function(){
                        $(this).dialog('close');
                    });
                });
            },
            
            "Cancelar" : function(){
                $(this).dialog('close');
            }

        }
    });
    
    $( "#accordion" ).accordion({
        autoHeight: false,
        navigation: true,
        collapsible: true
    });    
    
    /**
     * Diálogo popup variables de entorno
     */
    $( "#dialogoVarEnv" ).dialog({
        autoOpen: false,
        modal: false,
        width: 400,
        height: 350,
        position: ['right','center'],
        closeOnEscape: true
    });

    /**
     * Diálogo popup para ordenaciones
     */
    $( "#dialogOrdenar" ).dialog({
        autoOpen: false,
        width: 500,
        height: 500,
        /*position: ['right','center'],*/     
        closeOnEscape: true
    });
 
    /**
     * Diálogo para notificaciones
     */
    $( "#notificacion" ).dialog({                       
        resizable: true,
        modal: true,
        buttons: {
        Aceptar: function() { $(this).dialog( "close" ); }
        },        
        autoOpen: false,
        dialogClass: "alert",        
        width: 400,
        height: 200,
        position: ['center','center'],     
        closeOnEscape: true
    });
    
    /**
     * Para el efecto acordeón
     */
    $( "#acordeonColumnas" ).accordion({
        autoHeight: false,
        navigation: true,
        collapsible: true,
        active: false,
        heightStyle: "content"
    });
    
    $( "#acordeonVW" ).accordion({
        autoHeight: false,
        navigation: true,
        collapsible: true,
        active: false,
        heightStyle: "content"
    });    
    
    $( "#acordeonOrdenesWeb" ).accordion({
        autoHeight: false,
        navigation: true,
        collapsible: true,
        active: false,
        heightStyle: "content"
    });

    $("#usersSelector").change(function () {
        submitForm('layoutForm');
    });

    $("#projectsSelector").change(function () {
        submitForm('layoutForm');
    });

});

/**
 * Pinta los totales de presupuestos, albaranes y pedidos
 * @param {type} entidad
 * @param {type} idEntidad
 * @returns {undefined}
 */
function pintaTotales(entidad,idEntidad) {
    var url = appPath +"/lib/getObjeto.php?formato=JSON&entidad="+entidad+"&idEntidad="+idEntidad;
 
    $.getJSON( url, function( data ) {

        parent.document.getElementById(entidad+"_LiteralTotal").innerHTML="TOTAL "+data['Total'];
        parent.document.getElementById(entidad+"_Importe").value=data['Importe'];
        parent.document.getElementById(entidad+"_Descuento").value=data['Descuento'];
        parent.document.getElementById(entidad+"_BaseImponible1").value=data['BaseImponible1'];
        parent.document.getElementById(entidad+"_BaseImponible2").value=data['BaseImponible2'];
        parent.document.getElementById(entidad+"_BaseImponible3").value=data['BaseImponible3'];
        parent.document.getElementById(entidad+"_Iva1").value=data['Iva1'];
        parent.document.getElementById(entidad+"_Iva2").value=data['Iva2'];
        parent.document.getElementById(entidad+"_Iva3").value=data['Iva3'];
        parent.document.getElementById(entidad+"_CuotaIva1").value=data['CuotaIva1'];
        parent.document.getElementById(entidad+"_CuotaIva2").value=data['CuotaIva2'];
        parent.document.getElementById(entidad+"_CuotaIva3").value=data['CuotaIva3'];
        parent.document.getElementById(entidad+"_Recargo1").value=data['Recargo1'];
        parent.document.getElementById(entidad+"_Recargo2").value=data['Recargo2'];
        parent.document.getElementById(entidad+"_Recargo3").value=data['Recargo3'];
        parent.document.getElementById(entidad+"_CuotaRecargo1").value=data['CuotaRecargo1'];
        parent.document.getElementById(entidad+"_CuotaRecargo2").value=data['CuotaRecargo2'];
        parent.document.getElementById(entidad+"_CuotaRecargo3").value=data['CuotaRecargo3'];
        parent.document.getElementById(entidad+"_TotalBases").value=data['TotalBases'];
        parent.document.getElementById(entidad+"_TotalIva").value=data['TotalIva'];
        parent.document.getElementById(entidad+"_TotalRecargo").value=data['TotalRecargo'];

    }); 

}

function solapaActiva(solapa) {
    alert(solapa);
//$("#solapaActiva").val(solapa);
}

function AcordeonActivo(acordeon) {
    $("#acordeonActivo").val(acordeon);
}

/**
 * Oculta (si existe) el elemento html id
 * @param id
 * @return void
 */
function ocultarElemento(id) {

    if ($('#'+id).length){
        $('#'+id).css("display", "none");
    }
}

/**
 * Muestra (si existe) el elemento html id
 * @param id
 * @return void
 */
function mostrarElemento(id) {

    if ($('#'+id).length){
        $('#'+id).css("display", "block");
    }
}

/**
 * Muestra/oculta el elemento id
 */
function switchDisplay(id) {
    if ($('#'+id).css('display') == 'block') {
        $('#'+id).css('display','none');
    } else {
        $('#'+id).css('display','block')        
    }
}
/**
 * Muestra un popUp con las variables de entorno
 * un formulario de mantenimiento de las variables de entorno
 * del modulo y columna indicado en 'modulo_columna'
 *
 * En el parámetro 'modulo_columna' debe venir el nombre del módulo y el
 * de la columna concatenados con guión bajo
 *
 *
 * @param tipo El tipo de variable
 * @param ambito El ámbito: Pro,App,Mod
 * @param modulo_columna El nombre del modulo y de la columna concatenados con guión bajo
 * @return void
 */
function popUpVariablesEnv(tipo, ambito, modulo_columna) {

    var elementos = modulo_columna.split("_");

    var modulo = elementos[0];
    var columna = elementos[1];
    var url = '<iframe src="' + appPath + '/CpanVariables/EditNode/' + ambito + '/' + tipo + '/' + modulo + '/' + columna + '" width="100%" height="98%"></iframe>';

    $('#dialogoVarEnv').html(url);
    $('#dialogoVarEnv').dialog('open');  
}

/**
 * Muestra el popUp de ordenacion
 */
function popUpOrdenar(controller,columna,key,columnaMostrar) {
    
    var url = '<iframe src="' + appPath + '/Ordenar/Index/' + controller + '/' + columna + '/' + key + '/' + columnaMostrar + '" width="100%" height="98%"></iframe>';

    $('#dialogOrdenar').html(url);
    $('#dialogOrdenar').dialog('open');   
}

function cargaEtiquetasRelacionadas(idDiv,idModulo,primaryKey) {

    var url = '<iframe src="' + appPath + '/EtiqRelaciones/list/' + idModulo + '/' + primaryKey + '" width="100%" height="520"><p>Tu navegador no soporta iframes</p></iframe>';

    $('#'+idDiv).html(url);
}

function Confirma(mensaje) {
    var dialogo = $('<div title="Confirmación"><p>' + mensaje + '</p></div>');
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $( "#dialog:ui-dialog" ).dialog( "destroy" );

    dialogo.dialog({
        autoOpen: true,
        dialogClass: "alert",
        resizable: false,
        height: 150,
        modal: true,
        show: "fold",
        hide: "scale",
        buttons: {
            Aceptar: function() {
                $( this ).dialog( "close" );
                return true;
            },
            Cancelar: function() {
                $( this ).dialog( "close" );
                return false;
            }
        }
    });
}

/**
 * Submitea el formulario pasado como parámetro
 *
 * @param string formulario El nombre del formulario a submitear
 * @return void
 */
function submitForm(formulario) {
    
    // Coger el número de acordeon que está activo
    //var acordeonActivo = $( "#accordion" ).accordion( "option", "active" );
    
    // Poner el número acordeon activo para que se submitee
    //$('#acordeonActivo').val(acordeonActivo);
    
    $('#'+formulario).submit();
}

function Confirma1(mensaje){
    if (confirm(mensaje)) return true;
    else return false;
}

function CerrarVentana() {
    window.close();
}

function Redondear(cantidad, decimales) {
    var vcantidad = parseFloat(cantidad);
    var vdecimales = parseFloat(decimales);
    
    vdecimales = (!vdecimales ? 2 : vdecimales);
    
    return Math.round(vcantidad * Math.pow(10, vdecimales)) / Math.pow(10, vdecimales);
}

function borraMetadato(entidad,metadato) {
    var url        = appPath + '/lib/metaDatos.php';
    var parametros = 'entidad='+entidad+'&metadato='+metadato+'&accion=B';
    var html;
    
    // Coloco un gif "Cargando..." en la capa
    html = $('#div_metaDato_'+metadato).html();    
    $('#div_metaDato_'+metadato).html("<img src='"+appPath+"/images/loading.gif'>");

    $('#resultadoMetaDatos').load(url, parametros, function(){
        if ($('#resultadoMetaDatos').html() === '1') {
            $('#div_metaDato_'+metadato).html('');        
        } else {
            $('#div_metaDato_'+metadato).html(html);        
        }        
    });

}

function creaMetadato(entidad,metadato,idEntidad) {
    
    // Sustituir todos los espacios con guiones bajos
    metadato = metadato.replace(/ /g,"_");
    
    var url        = appPath + '/lib/metaDatos.php';
    var parametros = 'entidad='+entidad+'&metadato='+metadato+'&idEntidad='+idEntidad+'&accion=C';
    var html, htmlNuevo;

    if (metadato !== '') {
        $('#resultadoMetaDatos').load(url,parametros,function(){
            if ($('#resultadoMetaDatos').html() === '1') {
                $('#metaDato_nuevo').val('');
                html = $('#div_metaDatos').html();
                htmlNuevo = "<div id='div_metaDato_"+metadato+"' class='Item'><span class='Etiqueta'><span>"+metadato+"</span></span><textarea name='metaDato["+metadato+"]' id='metaDato_"+metadato+"' class='TextAreaMeta' rows='2' columns='75'></textarea></div></div>";
                html = html + htmlNuevo;
                $('#div_metaDatos').html(html);
            }
            if ($('#resultadoMetaDatos').html() === '2') {
                alert("Ese campo ya existe");
            }            
        });
    } else {
        alert('Debe indicar un nombre');
    }
}

function ValidaNif(idDiv) {
    cadena = "TRWAGMYFPDXBNJZSQVHLCKET";
    mensaje='';
    caracteres = $('#'+idDiv).val().length;
    if ((caracteres < 7) || ( caracteres > 9)) {
        mensaje = 'Faltan caracteres';
    }
    else {
        nif=$('#'+idDiv).val();
        primero=nif.substring(0,1);
        if (!isNaN(primero)){
            numeros=nif.substring(0,8);
            letra=nif.substring(8,1);
            posicion=numeros % 23;
            letraok=cadena.substring(posicion,posicion+1);
            $('#'+idDiv).val(numeros + letraok);
        }
    }
    if(mensaje!==''){
        $('#notificacion').html(mensaje);
        $('#notificacion').dialog('open');    
    }
}

function esNumero(numero){
    
 return (/^([0-9])*.([0-9])*$/.test(numero));
 
}
 
function IsNumeric(valor){   
    return !isNaN(valor);
}

var primerslap=false;
var segundoslap=false;

function formateafecha(fecha){
    var longitud = fecha.length;
    var dia;
    var mes;
    var ano;
    if ((longitud>=2) && (primerslap==false)) {
        dia=fecha.substr(0,2);
        if ((IsNumeric(dia)==true) && (dia<=31) && (dia!="00")) {
            fecha=fecha.substr(0,2)+"/"+fecha.substr(3,7);
            primerslap=true;
        }
        else {
            fecha="";
            primerslap=false;
        }
    }
    else
    {
        dia=fecha.substr(0,1);
        if (IsNumeric(dia)==false)
        {
            fecha="";
        }
        if ((longitud<=2) && (primerslap=true)) {
            fecha=fecha.substr(0,1);
            primerslap=false;
        }
    }
    if ((longitud>=5) && (segundoslap==false))
    {
        mes=fecha.substr(3,2);
        if ((IsNumeric(mes)===true) &&(mes<=12) && (mes!=="00")) {
            fecha=fecha.substr(0,5)+"/"+fecha.substr(6,4);
            segundoslap=true;
        }
        else {
            fecha=fecha.substr(0,3);
            segundoslap=false;
        }
    }
    else {
        if ((longitud<=5) && (segundoslap==true)) {
            fecha=fecha.substr(0,4);
            segundoslap=false;
        }
    }
    if (longitud>=7)
    {
        ano=fecha.substr(6,4);
        if (IsNumeric(ano)==false) {
            fecha=fecha.substr(0,6);
        }
        else {
            if (longitud === 10){
                if ((ano === 0) || (ano<1900) || (ano>2100)) {
                    fecha=fecha.substr(0,6);
                }
            }
        }
    }
    if (longitud>=10){
        fecha=fecha.substr(0,10);
        dia=fecha.substr(0,2);
        mes=fecha.substr(3,2);
        ano=fecha.substr(6,4);
        // A�o no viciesto y es febrero y el dia es mayor a 28
        if ( (ano%4 !== 0) && (mes === 02) && (dia > 28) ) {
            fecha=fecha.substr(0,2)+"/";
        }
    }
    return (fecha);
}

function loading(iddiv) {
    // Coloco un gif "Cargando..." en la capa
    var html = "<img src='"+appPath+"/images/loadingAnimation.gif'/>";
    $('#'+iddiv).html(html);
}

/*
 * ----------------------------------------------------------------
 * FUNCIONES AJAX
 * ----------------------------------------------------------------
 * GENERA UN DESPLEGLABE CON TECNOLOGIA AJAX. LOS PARAMETROS SON
 *
 * iddiv        -> ID identificador del contenedor que será rellenado con la respuesta (¡¡no vale el atributo name!!)
 * idselect     -> ID identificador del select que se creará  (¡¡no vale el atributo name!!)
 * nameselect   -> NAME name del select que se creará (se utiliza para capturar del datos del formulario)
 * tipo         -> indica el tipo de select. O sea la tabla que se empleará. Ver script 'desplegableAjax.php'
 * filtro       -> elemento html que contiene el valor por el que filtrar los datos.
 */
function DesplegableAjax(iddiv,idselect,nameselect,tipo,filtro) {
    var url        = appPath + '/lib/desplegableAjax.php';
    var parametros = 't='+tipo+'&filtro='+filtro+'&idselect='+idselect+'&nameselect='+nameselect;

    // Coloco un gif "Cargando..." en la capa
    $('#'+iddiv).html("<img src='"+appPath+"/images/loading.gif'>");

    jQuery('#'+iddiv).load(url, parametros);

}

/*
 * GENERA UN LISTA DE AUTOCOMPLETADO CON JQUERY. REQUIRE DE LA FUNCION 'devuelve'
 *
 * campoAutoCompletar   -> es el id del campo donde se genera el autocompletado
 * campoId              -> es el id del campo donde se devuelve el id obtenido
 * campoTexto           -> es el id del campo donde se devuelve el texto obtenido
 * entidad              -> indica en base a qué entidad de datos se genera el autocompletado
 * idSucursal           -> valor de la sucursal, es opcional
 * desplegableAjax      -> array con parametros adicionales para disparar desplegables en cascada
 */
function autoCompletar(campoAutoCompletar,campoId,campoTexto,entidad,idSucursal,desplegableAjax) {
    $( "#"+campoAutoCompletar).autocomplete({
        source: appPath + "/lib/autoCompletar.php?idSucursal=" + idSucursal + "&entidad=" + entidad,
        minLength: 2,
        select: function( event, ui ) {
            devuelve( campoId, ui.item.id, campoTexto, ui.item.value, desplegableAjax );
        }
    });
}

/*
 * GENERA UN LISTA DE AUTOCOMPLETADO CON JQUERY. REQUIRE DE LA FUNCION 'devuelve'
 *
 * campoAutoCompletar   -> es el id del campo donde se genera el autocompletado
 * campoId              -> es el id del campo donde se devuelve el id obtenido
 * campoTexto           -> es el id del campo donde se devuelve el texto obtenido
 * entidad              -> indica en base a qué entidad de datos se genera el autocompletado
 * columna              -> la columna de la entidad de datos que se devolverá
 * filtroAdicional      -> valor para un filtro adicional, es opcional
 * desplegableAjax      -> array con parametros adicionales para disparar desplegables en cascada
 */
function autoComplete(campoAutoCompletar,campoId,campoTexto,entidad,columna,filtroAdicional,desplegableAjax) {

    var url = appPath + "/lib/autoCompletar.php?entidad=" + entidad + "&columna=" + columna + "&filtroAdicional=" + filtroAdicional;

    $("#"+campoAutoCompletar).autocomplete({
        source: url,
        minLength: 2,
        select: function( event, ui ) {
            devuelve( campoId, ui.item.id, campoTexto, ui.item.value, desplegableAjax );
        }
    });
}

function devuelve( campoId, id, campoTexto, value, desplegableAjax) {
    $( "#"+campoId ).val(id);
    $( "#"+campoTexto ).val(value);
    if (desplegableAjax.length > 0) {
        var params = desplegableAjax;
        DesplegableAjax(params[0],params[1],params[2],params[3],id);
    }
    $( "#"+campoTexto ).focus();
}

/**
 * Actualiza vía ajax la 'columna' de la 'entidad' e 'idEntidad' con el 'valor'
 * 
 * Esta función sirve para actualizar cualquier columna de cualquier entidad
 * con un valor dado vía ajax
 * 
 */
function actualizaColumna(entidad,idEntidad,columna,valor) {

    var parametros = 'entidad='+entidad+'&idEntidad='+idEntidad+'&columna='+columna+'&valor='+valor;

    $.ajax({
        url: appPath + "/lib/actualizaColumna.php",
        type: 'GET',
        async: true,
        data: parametros
    });
}

/**
 * Actualiza las variables de entorno de las columnas de las entidades
 */
function actualizaVarEntorno(entidadColumnaPropiedad,valor) {
    var parametros = 'entidadColumnaPropiedad='+entidadColumnaPropiedad+'&valor='+valor;

    $.ajax({
        url: appPath + "/lib/actualizaVarEntorno.php",
        type: 'GET',
        async: true,
        data: parametros
    });    
}

/**
 * Asigna/Quita permiso a los módulos y perfiles de usuarios del Erp
 */
function actualizaPermiso(idPerfil,nombreModulo,permiso,valor) {
    var parametros = 'idPerfil='+idPerfil+'&nombreModulo='+nombreModulo+'&permiso='+permiso+'&valor='+valor;

    $.ajax({
        url: appPath + "/lib/actualizaPermiso.php",
        type: 'GET',
        async: true,
        data: parametros
    });    
}

/**
 * Crear o borra la relación entre una entidad-id y otra entidad-id
 */
function actualizaRelacion(entidadOrigen,idOrigen,entidadDestino,idDestino,onOff) {
    var parametros = 'entidadOrigen='+entidadOrigen+'&idOrigen='+idOrigen+'&entidadDestino='+entidadDestino+'&idDestino='+idDestino+'&onOff='+onOff;

    $.ajax({
        url: appPath + '/lib/actualizaRelacion.php',
        type: 'GET',
        async: true,
        data: parametros
    });    
}

function actualizaEtiquetasRelacionadas(idModulo,idEntidad,idEtiqueta,onOff) {

    var parametros = 'idModulo='+idModulo+'&idEntidad='+idEntidad+'&idEtiqueta='+idEtiqueta+'&onOff='+onOff;

    $.ajax({
        url: appPath + '/lib/etiquetasRelacionadas.php',
        type: 'GET',
        async: true,
        data: parametros
    });
}

/**
 * Actualiza la tripleta idarticulo-idpropiedad-idvalor
 **/
function actualizaArticuloPropiedad(idArticulo,idPropiedad,idValor) {
    var url = appPath + '/lib/actualizaArticuloPropiedad.php';
    var parametros = 'idArticulo='+idArticulo+'&idPropiedad='+idPropiedad+'&idValor='+idValor;
    $('#resultado').load(url, parametros);
}

/**
 * Actualiza la tabla de portes
 */
function actualizaTablaPortes(idAgencia,idZona,kilos,importe) {
    var url = appPath + '/lib/actualizaTablaPortes.php';
    var parametros = 'idAgencia='+idAgencia+'&idZona='+idZona+'&kilos='+kilos+'&importe='+importe;

    $('#resultado').load(url, parametros);
    
}

/**
 * Actualiza la relacion familias-propiedades
 */
function actualizaFamiliaPropiedad(idFamilia,idPropiedad,valor) {
    var url = appPath + '/lib/actualizaFamiliaPropiedad.php';
    var parametros = 'idFamilia='+idFamilia+'&idPropiedad='+idPropiedad+'&valor='+valor;

    $('#resultado').load(url, parametros);
    
}
/**
 * Actualiza la columna 'Filtrable' de una familia y propiedad
 */
function actualizaFamiliaPropiedadFiltrable(idFamilia,idPropiedad,valor) {
    var url = appPath + '/lib/actualizaFamiliaPropiedadFiltrable.php';
    var parametros = 'idFamilia='+idFamilia+'&idPropiedad='+idPropiedad+'&valor='+valor;

    $('#resultado').load(url, parametros);
    
}
/*
 * ----------------------------------------------------------------
 * FUNCION PARA LOS MENSAJES DE NOTIFICACION
 * NECESITA:
 *       notificaciones.css
 *       habilitar un tag <div id="notificacion"></div> en cualquier
 *       parte de la pagina, preferiblemente en el layout principal
 *
 * $mensaje: es el mensaje de notificacion a mostrar
 * $tipo:    determina el estilo del mensaje. Los valores posibles son:
 *           exito,alerta,info,error (que coinciden con los estilos
 *           definidos en notificaciones.css)
 *
 * ----------------------------------------------------------------
 */
function notificacion(mensaje,tipo) {
    var v=0;
    var errores="";
    
    for (var i=0; i<=mensaje.length;i++)
    {
        if(mensaje[i]==='#')
        {
            errores += '<p>' + mensaje.substring(i, v) + '</p>';
            v=i+1;
        }
    }

    $('#notificacion').html(errores);
    $('#notificacion').dialog('open');
}

/**
 * CREA UN LOTE Y RECARGA EL DIV iddiv CON EL SELECT DE TODOS
 * LOS LOTE DEL PRODUCTO idArticulo
 * SI NO SE INDICA iddiv, SE CREA EL LOTE PERO NO SE RECARGA EL DIV
 * EL PARAMETRO puntero SE UTILIZA PARA EL ID DEL TAG <SELECT>
 */
function CrearLote(puntero,iddiv,idSelect,nameSelect,idArticulo,lote,fFabricacion,fCaducidad,width) {
    var url        = appPath + '/lib/crearlote.php';
    var parametros = 'puntero='+puntero+'&idArticulo='+idArticulo+'&lote='+lote+'&fFabricacion='+fFabricacion+'&fCaducidad='+fCaducidad+'&idSelect='+idSelect+'&nameSelect='+nameSelect+'&width='+width;

    // Coloco un gif "Cargando..." en la capa
    $('#'+iddiv).html("<img src='"+appPath+"/images/loading.gif' />");

    jQuery('#'+iddiv).load(url, parametros);

}

/**
 * FUNCTION PARA AUTOCOMPLETAR
 *
 * PARAMETROS:
 *      key:        Para identificar los divs que hay que rellenar u ocultar
 *      idSucursal: EL ID de la sucursal para filtrar clientes y articulos
 *      idInput:    EL ID del campo donde hay que devolver el id del resultado
 *      idTexto:    El ID del campo donde hay que devolver el texto del resultado
 *      entidad:    Para saber que tipo de consulta debe hacer el script php (autoCompletar.php)
 *      valor:      El valor introducido por el usuario
 *
 * SE APOYA EN LA FUNCION fill, DESCRITA MÁS ABAJO
 * 
 * POR RAZONES DE OPTIMIZACION, NO SE LANZA LA BUSQUEDA HASTA QUE NO SE HAN
 * INTRODUCIDO AL MENOS TRES CARACTERES.
 *
 * NECESITA LA HOJA DE ESTILOS jquery.autocompletar.css
 *
 * LLAMA AL SCRIP autoCompletar.php QUE ES EL QUE HACE LA CONSULTA
 * A LA BASE DE DATOS Y DEVUELVE EL RESULTADO
 */
function lookup(key,idSucursal,idInput,idTexto,entidad,valor,desplegableAjax) {

    if(valor.length < 3) {
        // Hide the suggestion box.
        $('#suggestions'+key).hide();
    } else {
        $.post(
            appPath + "/lib/autoCompletar.php",
            {
                key: ""+key+"",
                idSucursal: ""+idSucursal+"",
                idInput: ""+idInput+"",
                idTexto: ""+idTexto+"",
                entidad: ""+entidad+"",
                valor: ""+valor+"",
                desplegableAjax: ""+desplegableAjax+""
            },
            function(data){
                if(data.length >0) {
                    $('#suggestions'+key).show();
                    $('#autoSuggestionsList'+key).html(data);
                }
            }
            );
    }
}

/**
 * PONE EL VALOR value EN EL CAMPO idInput
 * PONE EL VALOR texto EN EL CAMPO idTexto
 * Y OCULTA EL DIV suggestions+key
 *
 * ESTA FUNCION ES UTILIZADA POR LA FUNCION lookup
 *
 * SI desplegableAjax TIENE VALOR, SE ENTIENDE QUE ES UN ARRAY
 * DE VALORES SEPARADOR POR COMAS Y SERAN UTILIZADOS COMO
 * PARAMETROS PARA LA FUNCION DesplegableAjax
 */
function fill(key,idInput,value,idTexto,texto, desplegableAjax) {

    $('#'+idInput).val(value);
    $('#'+idTexto).val(texto);
    setTimeout("$('#suggestions"+key+"').hide();", 200);

    if (desplegableAjax.length > 0){
        var params = desplegableAjax.split(',');
        DesplegableAjax(params[0],params[1],params[2],params[3],value);
    }
}

/**
 * OCULTA EL DIV suggestions+key
 */
function hideSuggestions(key) {
    setTimeout("$('#suggestions"+key+"').hide();", 200);
}

function documentos(entidad, id, idDiv) {
    var url        = appPath + '/lib/documentos.php';
    var parametros = 'id='+id+'&entidad='+entidad;

    // Coloco un gif "Cargando..." en la capa
    $('#'+idDiv).html("<img src='"+appPath+"/images/loading.gif'>");

    jQuery('#'+idDiv).load(url, parametros); 
}


function isEmail(email)
{
	var posArroba = email.indexOf('@',0);
	
	if (posArroba <= 0)
		return false;

	var posPunto = email.indexOf('.',posArroba);
		
	if (posPunto == -1)
		return false;
		
	if (posPunto+1 == email.length)
		return false;
	// Despues del punto solo puede haber: a-z 0-9 . _-
	if (!contieneCaracteresPermitidos(email.substr(posPunto+1), "._-"))
		return false;

	return true;
}

function isAlfanumerico(valor)
{
	var longi = valor.length;
	var c;
	valor = valor.toLowerCase();
	
	if (longi>0) {
		c = valor.charAt(0);
		if (!(c >= 'a' && c <= 'z')) {
			return false;
		}
	}
	
	for (var i = 1; i < longi; i++)
	{
		c = valor.charAt(i);
		if ((c >= '0' && c <= '9') || (c >= 'a' && c <= 'z') || c=='_' || c=='.')
			continue;
		else 
			return false;
	}
	return true;
}

function contieneCaracteresPermitidos(valor, caracteresValidos)
{
	var longi = valor.length;
	var c;
	valor = valor.toLowerCase();
	
	for (var i = 0; i < longi; i++)
	{
		c = valor.charAt(i);
		if ((c >= '0' && c <= '9') || (c >= 'a' && c <= 'z')) {
			continue;
		} else {
			for (var j=0; j<caracteresValidos.length; j++) {
				if (caracteresValidos.indexOf(c)==-1) {
					return false;
				}
			}
		}
	}
	return true;
}