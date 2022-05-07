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
                        Nome: {{ $user->name }} <br/>
                        Email: {{ $user->email }} <br/>
                        Data di inserimento: {{ $user->created_at->format('d/m/Y') }} <br/>
                        Ruolo: {{ $user->roleName() }} <br/>
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
        <h1>Dettagli utente</h1>
    </div>

    <div class="footer">
        <h3></h3>
    </div>
</body>
