<style>
    body {
        height: 21cm;
        width: 25cm;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
        margin:auto;
        font-family: sans-serif;
    }
    table {
        border-collapse: collapse;
        border:none;
    }
    table td, table th {
        border: 1px solid black;
    }

    @media print {
        body {
            height: 21.59cm;
            width: 27.94cm;
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
            margin:auto;
            font-family: sans-serif;
        }

        tr {
            page-break-inside:avoid; page-break-after:auto
        }
    }
</style>

<table  cellpadding="3" width="100%" style="font-size: 7pt; page-break-inside:avoid;">
    <tr>
        <td rowspan="3" width="4%"><img src="<?= URL_IMAGE.$this->session->company ?>" width="140px" height="60px" /></td>
        <td colspan="4" style="text-align: center;"><h3>REPORTE DE INSUMOS PENDIENTES</h3></td>
    </tr>
    <tr>
        <td colspan="2">CLIENTE:&nbsp;&nbsp; <?= $head->client ?></td>
        <td>OBRA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $head->project ?></td>
        <td>PEDIDO <?= $head->type ?>-<?= $head->order ?></td>


    </tr>
    <tr>
        <td>CODIGO:&nbsp;&nbsp; VA-24</td>
        <td><b>ENTREGA N&deg; </b></td>
        <td style="width: 90px">VERSION:&nbsp;&nbsp; 01</td>
        <td></td>
    </tr>
</table>
<br>
<table cellpadding="3" width="100%" style="font-size: 7pt;">
    <thead>
        <tr>
            <td style="text-align: center;font-size: 120%;">Referencia</td>
            <td style="text-align: center;font-size: 120%;">Nombre</td>
            <td style="text-align: center;font-size: 120%;width: 5%;">Cantidad total</td>
            <td style="text-align: center;font-size: 120%;width: 5%;">Cantidad empacada</td>
            <td style="text-align: center;font-size: 120%;width: 5%;">Total pendiente</td>
        </tr>
    </thead>
    <tbody>