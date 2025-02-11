<style>
    body {
        font-family: Arial, Helvetica, sans-serif
    }
    .header, .header-space,
    .footer, .footer-space {
        height: 100px;
    }
    .header {
        position: fixed;
        top: 0;
    }
    .footer {
        position: fixed;
        bottom: 0;
    }
</style>

<body>
    <table>
        <thead>
            <tr>
                <td>
                    <div class="header-space">&nbsp;</div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="content">
                        Nome: {{ mb_convert_encoding($course->name, 'UTF-8', 'UTF-8') }} <br/>
                        Descrizione: {{ mb_convert_encoding($course->description, 'UTF-8', 'UTF-8') }} <br/>
                        Data di inserimento: @php echo \Carbon\Carbon::parse($course->created_at)->format('d/m/Y'); @endphp <br/>
                        Ultima modifica: @php echo \Carbon\Carbon::parse($course->updated_at)->format('d/m/Y'); @endphp <br/>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td>
                    <div class="footer-space">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="header">
        <h1>Dettagli corso</h1>
    </div>

    <div class="footer">
        <h3></h3>
    </div>
</body>
