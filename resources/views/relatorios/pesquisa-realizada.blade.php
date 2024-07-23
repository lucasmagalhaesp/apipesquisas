<style>
    *{
        font-family: Courier;
    }
</style>
<div style="margin-top: -20px">
    <div style="background: #2da3bc; color: #FFF; padding: 10px 0">
        <table width="100%">
            <tr width="100%">
                <td width="30%" style="padding-left: 20px">
                    <img src="imagens/img_relatorio.png" alt="logo_relatório" style="max-height: 150px">
                </td>
                <td width="70%" style="padding-left: 20px">
                    <h2 style="text-align: center; margin-top: 0">Dados da Pesquisa nº {{ $dados->id }}</h2>
                    <p><strong>Data:</strong> {{ $dados->created_at }}</p>
                    <p><strong>Pesquisa:</strong> {{ $dados->pesquisa->titulo }}</p>
                    <p><strong>Nome do Agente:</strong> {{ $dados->agente->nome }}</p>
                    @if($dados->pesquisa->tipo_entrevistado == "C")
                        <p><strong>Nome do entrevistado:</strong> {{ $dados->entrevistado->nome }}</p>
                    @else
                        <p><strong>Tipo do entrevistado:</strong> {{ $dados->pesquisa->tipo_entrevistado == "A" ? "Anônimo" : "Cadastrado" }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>
    <div style="border: 1px #0d8aa6 solid; margin-top: 10px">
        <div style="text-align: center; background: #2da3bc; color: #FFF">
            <h2 style="padding: 8px 0; margin-top: 0">Perguntas</h2>
        </div>
        <div style="padding: 0 15px">
            @foreach($listaPerguntas as $index => $perg)
                <h3 style="color: #0d8aa6">{{ $index + 1 }} - {{ $perg["pergunta"] }}</h3>
                <p><strong>R:</strong> {{ $perg["resposta"] }}</p>
            @endforeach
        </div>
    </div>
</div>