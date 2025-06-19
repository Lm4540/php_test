<!DOCTYPE html>
<html lang="es">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Factura Electrónica</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
      <style>
            .bold {
                  font-weight: bold;
                  font-size: 1rem;
            }

            body {
                  font-size: 0.8rem;
                  /* Letra más pequeña para todo el cuerpo */
            }

            .invoice-container {
                  max-width: 800px;
                  margin: 1rem auto;
                  /* Margen reducido */
                  background-color: #fff;
            }

            .invoice-header {
                  border-bottom: 2px solidrgb(0, 0, 0);
                  padding-bottom: 1rem;
                  margin-bottom: 1.5rem;
            }

            .invoice-title {
                  font-weight: bold;
                  color: #495057;
                  font-size: 1.1rem;
            }

            .info-box {
                  border: 1px solid #ced4da;
                  padding: 0.8rem;
                  border-radius: 0.25rem;
            }

            .info-box h5 {
                  font-size: 0.9rem;
                  /* Tamaño de títulos de cajas reducido */
                  font-weight: bold;
                  color: rgb(61, 61, 61);
                  margin-bottom: 0.75rem;
            }

            .totals-table {
                  font-size: 0.85rem;
            }

            .totals-table td:first-child {
                  font-weight: bold;
                  text-align: right;
                  padding-right: 1rem;
            }

            .qr-code-placeholder {
                  width: 120px;
                  height: 120px;
                  border: 2px dashed #adb5bd;
                  display: flex;
                  align-items: center;
                  justify-content: center;
                  color: #6c757d;
                  font-size: 0.8rem;
                  text-align: center;
                  margin-top: 1rem;
            }

            .table {
                  font-size: 0.8rem;
                  /* Letra más pequeña en la tabla */
            }

            .table thead th {
                  background-color: #e9ecef;
            }
      </style>
</head>

<body>

      <div class="invoice-container" id="factura">
            <!-- SECCIÓN DE ENCABEZADO Y TÍTULOS -->
            <header class="invoice-header">
                  <div class="row align-items-center">
                        <div class="col-6 text-center mb">
                              <img src="/img/logo3.png" alt="Logo" style="width: 75mm; ">
                        </div>
                        <div class="col-6 text-center">
                              <p class="bold"> DOCUMENTO TRIBUTARIO ELECTRÓNICO <br>FACTURA</p>
                        </div>
                  </div>

                  <div class="row align-items-center">
                        <div class="col-6">
                              <p class="mb-1">
                                    <strong>Código de Generación:</strong><br>
                                    <span id="codigoGeneracion"><?= $data->identificacion->codigoGeneracion ?></span>
                              </p>
                              <p class="mb-1">
                                    <strong>Número de Control:</strong> <br>
                                    <span id="numeroControl"><?= $data->identificacion->codigoGeneracion ?></span>
                              </p>
                              <p class="mb-0">
                                    <strong>Sello Recepción:</strong>
                                    <span id="selloRecepcion"><?= $data->selloRecepcion ?></span>
                              </p>
                        </div>

                        <div class="col-3">
                              <div class="qr-code-placeholder" id="qrCodeContainer">
                                    <img src="/img/qr-placeholder.png" alt="QR Code Placeholder" id="qrCode">
                              </div>
                        </div>

                        <div class="col-3">
                              <p class="mb-0">
                                    <strong>Modelo de Facturación:</strong><br>
                                    <span id="tipoModelo"><?= $data->identificacion->codigoGeneracion ?></span>
                              </p>

                              <p class="mb-0">
                                    <strong>Fecha y Hora de Generación:</strong><br>
                                    <span id="fechaHoraGeneracion"><?= $data->identificacion->codigoGeneracion ?>
                                          <?= $data->identificacion->codigoGeneracion ?></span>
                              </p>
                        </div>
                  </div>



            </header>

            <!-- SECCIÓN DE EMISOR Y RECEPTOR -->
            <div class="row align-items-center">

                  <div class="col-6">
                        <div class="info-box h-100">
                              <h5>EMISOR</h5>
                              <p class="mb-1" id="emisorNombre"></p>
                              <p class="mb-1"><strong>NIT:</strong> <span id="emisorNit"></span></p>
                              <p class="mb-1"><strong>Actividad:</strong> <span id="emisorActividad"></span></p>
                              <p class="mb-1"><strong>NRC:</strong> <span id="emisorNrc"></span></p>
                              <p class="mb-1" id="emisorDireccion"></p>
                              <p class="mb-1"><strong>Tel:</strong> <span id="emisorTelefono"></span></p>
                              <p class="mb-0"><strong>Email:</strong> <span id="emisorCorreo"></span></p>
                        </div>

                  </div>
                  <div class="col-6">
                        <div class="info-box h-100">
                              <h5>RECEPTOR</h5>
                              <p class="mb-1" id="receptorNombre"></p>
                              <p class="mb-1"><strong>Tipo Documento:</strong> <span id="receptorTipoDoc"></span>
                              </p>
                              <p class="mb-1"><strong>Núm. Documento:</strong> <span id="receptorNumDoc"></span>
                              </p>
                              <p class="mb-1"><strong>Actividad:</strong> <span id="receptorActividad"></span></p>
                              <p class="mb-1" id="receptorDireccion"></p>
                              <p class="mb-1"><strong>Tel:</strong> <span id="receptorTelefono"></span></p>
                              <p class="mb-0"><strong>Email:</strong> <span id="receptorCorreo"></span></p>
                        </div>
                  </div>
            </div>

            <!-- SECCIÓN DE CUERPO DEL DOCUMENTO (ITEMS) -->
            <div class="table-responsive mb-4 mt-4">
                  <table class="table table-bordered table-sm">
                        <thead class="text-center">
                              <tr>
                                    <th>N°</th>
                                    <th>Cantidad</th>
                                    <th>Unidad</th>
                                    <th>Descripción</th>
                                    <th>Precio Unitario</th>
                                    <th>Ventas No Sujetas</th>
                                    <th>Ventas Exentas</th>
                                    <th>Ventas Gravadas</th>
                              </tr>
                        </thead>
                        <tbody id="cuerpoDocumento"></tbody>
                        <tfoot></tfoot>
                  </table>
            </div>

            <!-- SECCIÓN DE TOTALES Y QR -->
            <div class="row">
                  <!-- Columna Izquierda: Letras, Observaciones y QR -->
                  <div class="col-7">
                        <p><strong>Valor en Letras:</strong> <span id="totalLetras"></span></p>
                        <p><strong>Observaciones:</strong> <span id="observaciones"></span></p>
                        <p><strong>Condición de la Operación:</strong> <span id="condicionOperacion"></span></p>

                  </div>

                  <!-- Columna Derecha: Tabla de Totales -->
                  <div class="col-5">
                        <table class="table table-sm totals-table">
                              <tbody>
                                    <tr>
                                          <td>Sub-total:</td>
                                          <td id="subTotal" class="text-end"></td>
                                    </tr>
                                    <tr>
                                          <td>IVA Retenido:</td>
                                          <td id="ivaRete1" class="text-end"></td>
                                    </tr>
                                    <tr>
                                          <td>Retención Renta:</td>
                                          <td id="reteRenta" class="text-end"></td>
                                    </tr>
                                    <tr>
                                          <td>Monto Total Operación:</td>
                                          <td id="montoTotalOperacion" class="text-end"></td>
                                    </tr>
                                    <tr class="fw-bold fs-6">
                                          <td>Total a Pagar:</td>
                                          <td id="totalPagar" class="text-end"></td>
                                    </tr>
                              </tbody>
                        </table>

                  </div>
            </div>

            <!-- PIE DE PÁGINA -->
            <footer class="mt-4 pt-3 border-top text-muted text-center">
                  <p class="small">Versión: <span id="version"></span> | Fecha de Procesamiento: <span
                              id="fhProcesamiento"></span></p>
            </footer>
      </div>


      <!-- SCRIPT PARA POBLAR DATOS -->
      <script>
            // JSON de ejemplo, en una aplicación real vendría de un servidor
            const jsonData = { "identificacion": { "version": 1, "tipoDte": "01", "ambiente": "01", "numeroControl": "DTE-01-M001P004-000000006907559", "tipoModelo": 1, "tipoOperacion": 1, "fecEmi": "2025-05-31", "horEmi": "02:34:48", "tipoMoneda": "USD", "codigoGeneracion": "94C94EB9-69AD-4E99-B74A-571C0C0CBDE1" }, "emisor": { "nit": "06143101550016", "nrc": "5525", "nombre": "BANCO AGRICOLA SA", "codActividad": "64190", "descActividad": "BANCOS", "nombreComercial": "BANCOAGRICOLA", "direccion": { "departamento": "06", "municipio": "23", "complemento": "BLVD CONSTITUCION N100" }, "telefono": "22100000", "correo": "facturacionproveedoresba@bancoagricola.com.sv" }, "receptor": { "tipoDocumento": "36", "numDocumento": "055073066", "nombre": "LUIS MIGUEL RIVERA CORTEZ", "codActividad": "10001", "descActividad": "Empleados", "nrc": null, "telefono": "73117233", "correo": "luisrivera4540@gmail.com", "direccion": { "departamento": "05", "municipio": "25", "complemento": "COLONIA SAN LUIS, POL 3, Ñ7 ,COLON" } }, "resumen": { "totalNoSuj": 0, "totalExenta": 0, "totalGravada": 1.13, "montoTotalOperacion": 1.13, "totalPagar": 1.13, "totalLetras": "UNO 13/100", "condicionOperacion": 1, "subTotal": 1.13 }, "extension": { "observaciones": null }, "apendice": [{ "campo": "F.E.", "valor": "0031020475" }, { "campo": "C.C.", "valor": "1422" }, { "campo": "batch", "valor": "2347" }, { "campo": "Tipo de Servicio ", "valor": "07" }, { "campo": "Número Único ", "valor": "5146318" }], "cuerpoDocumento": [{ "numItem": 1, "cantidad": 1, "uniMedida": 99, "descripcion": "V/COBRO DE MEMBRESIA TARJETA CHEQUE MAX CORRESPONDIENTE A ESTE DIA.", "precioUni": 1.13, "ventaGravada": 1.13, "ventaNoSuj": 0, "ventaExenta": 0 }], "acuseMH": { "version": "1.0", "numValidacion": "20254649765267724120B022A355513A4213XQPM", "fhProcesamiento": "01/06/2025 14:54:50" } };

            function poblarDatos(data) {
                  const tipoDocMap = { '36': 'NIT', '13': 'DUI' }, 
                  condicionOpMap = { 1: 'Contado', 2: 'Crédito' }, 
                  unidadMedidaMap = { 99: 'Otra', 59: 'Unidad' }, 
                  tipoModeloMap = { 1: 'Modelo Facturación Previo' };
                  const formatCurrency = value => `$${Number(value).toFixed(2)}`;

                  document.getElementById('codigoGeneracion').textContent = data.identificacion.codigoGeneracion;
                  document.getElementById('numeroControl').textContent = data.identificacion.numeroControl;
                  document.getElementById('selloRecepcion').textContent = data.acuseMH.numValidacion;
                  document.getElementById('tipoModelo').textContent = tipoModeloMap[data.identificacion.tipoModelo] || 'Desconocido';
                  document.getElementById('fechaHoraGeneracion').textContent = `${data.identificacion.fecEmi} ${data.identificacion.horEmi}`;

                  document.getElementById('emisorNombre').textContent = data.emisor.nombre;
                  document.getElementById('emisorNit').textContent = data.emisor.nit;
                  document.getElementById('emisorActividad').textContent = data.emisor.descActividad;
                  document.getElementById('emisorNrc').textContent = data.emisor.nrc;
                  document.getElementById('emisorDireccion').textContent = data.emisor.direccion.complemento;
                  document.getElementById('emisorTelefono').textContent = data.emisor.telefono;
                  document.getElementById('emisorCorreo').textContent = data.emisor.correo;

                  document.getElementById('receptorNombre').textContent = data.receptor.nombre;
                  document.getElementById('receptorTipoDoc').textContent = tipoDocMap[data.receptor.tipoDocumento] || 'Otro';
                  document.getElementById('receptorNumDoc').textContent = data.receptor.numDocumento;
                  document.getElementById('receptorActividad').textContent = data.receptor.descActividad;
                  document.getElementById('receptorDireccion').textContent = data.receptor.direccion.complemento;
                  document.getElementById('receptorTelefono').textContent = data.receptor.telefono;
                  document.getElementById('receptorCorreo').textContent = data.receptor.correo;

                  const cuerpoDocTbody = document.getElementById('cuerpoDocumento');
                  cuerpoDocTbody.innerHTML = '';
                  let sumaNoSuj = 0, sumaExenta = 0, sumaGravada = 0;
                  data.cuerpoDocumento.forEach(item => {
                        sumaNoSuj += item.ventaNoSuj;
                        sumaExenta += item.ventaExenta;
                        sumaGravada += item.ventaGravada;
                        cuerpoDocTbody.innerHTML += `<tr><td class="text-center">${item.numItem}</td><td class="text-center">${item.cantidad}</td><td class="text-center">${unidadMedidaMap[item.uniMedida] || 'N/A'}</td><td>${item.descripcion}</td><td class="text-end">${formatCurrency(item.precioUni)}</td><td class="text-end">${formatCurrency(item.ventaNoSuj)}</td><td class="text-end">${formatCurrency(item.ventaExenta)}</td><td class="text-end">${formatCurrency(item.ventaGravada)}</td></tr>`;
                  });
                  cuerpoDocTbody.parentElement.querySelector('tfoot').innerHTML = `<tr><td colspan="5"></td><th class="text-end">SUMA:</th><td class="text-end">${formatCurrency(sumaExenta)}</td><td class="text-end">${formatCurrency(sumaGravada)}</td></tr>`;

                  document.getElementById('totalLetras').textContent = data.resumen.totalLetras;
                  document.getElementById('observaciones').textContent = data.extension.observaciones || 'Ninguna';
                  document.getElementById('subTotal').textContent = formatCurrency(data.resumen.subTotal);
                  document.getElementById('ivaRete1').textContent = formatCurrency(data.resumen.ivaRete1 || 0);
                  document.getElementById('reteRenta').textContent = formatCurrency(data.resumen.reteRenta || 0);
                  document.getElementById('montoTotalOperacion').textContent = formatCurrency(data.resumen.montoTotalOperacion);
                  document.getElementById('totalPagar').textContent = formatCurrency(data.resumen.totalPagar);
                  document.getElementById('condicionOperacion').textContent = condicionOpMap[data.resumen.condicionOperacion] || 'Otro';

                  document.getElementById('version').textContent = data.acuseMH.version;
                  document.getElementById('fhProcesamiento').textContent = data.acuseMH.fhProcesamiento;
            }

            // Poblar los datos iniciales al cargar la página
            document.addEventListener('DOMContentLoaded', () => {
                  poblarDatos(jsonData);
            });
      </script>

</body>

</html>