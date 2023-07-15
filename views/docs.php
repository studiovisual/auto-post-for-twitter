<div class="wrap sv-plugin docs">
    <h1>Como obter acesso à API do Twitter</h1>
    
    <h2>Passo 1: Cadastre-se para obter uma conta de desenvolvedor</h2>
    <p>O cadastro para uma conta de desenvolvedor é rápido e fácil! Basta <a href="https://developer.twitter.com/en/portal/products/basic" target="_blank">clicar aqui</a> para acessar o formulário de cadastro e responder algumas perguntas. <br />Assim, você poderá começar a explorar e construir na API do Twitter v2 usando acesso básico.</p>
    
    <h2>Passo 2: Salve a chave e os tokens do seu aplicativo e mantenha-os seguros</h2>
    <p>Assim que você tiver acesso e tiver criado um projeto e um aplicativo, você poderá encontrar ou gerar as seguintes credenciais dentro do seu aplicativo de desenvolvedor:</p>

    <ul>
        <li><strong>API Key e Secret:</strong> Essencialmente, o nome de usuário e a senha do seu aplicativo. Você usará esses dados para autenticar solicitações que exigem o Contexto do Usuário OAuth 1.0a ou para gerar outros tokens, como tokens de acesso do usuário ou token de acesso do aplicativo.</li>
        <li><strong>Access Token e Secret:</strong> Em geral, os Access Tokens representam o usuário em nome do qual você está fazendo a solicitação. Os tokens que você pode gerar por meio do portal de desenvolvedores representam o usuário que é proprietário do aplicativo. Você usará esses dados para autenticar solicitações que exigem o Contexto do Usuário OAuth 1.0a. Se você deseja fazer solicitações em nome de outro usuário, precisará usar o fluxo de autenticação de 3 etapas do OAuth para que eles autorizem você.</li>
        <li><strong>Client ID e Client Secret:</strong> Essas credenciais são usadas para obter um Access Token do usuário com autenticação OAuth 2.0. Semelhante ao OAuth 1.0a, os Access Tokens do usuário são usados para autenticar solicitações que fornecem informações privadas da conta do usuário ou executam ações em nome de outra conta, mas com escopo detalhado para um controle maior sobre o acesso que o aplicativo do cliente tem ao usuário.</li>   
        <li><strong>App only Access Token:</strong> Você usará esse token ao fazer solicitações para pontos de extremidade que respondem com informações disponíveis publicamente no Twitter.</li>
    </ul>

    <p>Já que essas chaves e tokens não expiram, a menos que sejam regenerados, sugerimos que você os salve em um local seguro, como um gerenciador de senhas, assim que receber suas credenciais.</p>

    <h2>Passo 3: Copiar as sua chaves geradas</h2>

    <p>Agora basta inserir suas chaves nos campos correspondentes na <a href="<?php echo admin_url('admin.php?page=' . $settingsPage); ?>" title="Configurações">configuração do plugin</a> e fazer suas customizações.</p>
</div>