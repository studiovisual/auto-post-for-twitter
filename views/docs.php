<?php 
/**
 * Docs
 * Docs of plugin
 * php version 8.1
 *
 * @category File
 * @package  Docs_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 //phpcs:disable
?>
<div class="wrap sv-plugin docs">
    <h1><?php echo _e('Como obter acesso à API do Twitter', 'sv-twitter'); ?></h1>
    
    <h2><?php echo _e('Passo 1: Cadastre-se para obter uma conta de desenvolvedor', 'sv-twitter'); ?></h2>
    <p><?php echo _e('O cadastro para uma conta de desenvolvedor é rápido e fácil! Basta <a href="https://developer.twitter.com/en/portal/products/basic" target="_blank">clicar aqui</a> para acessar o formulário de cadastro e responder algumas perguntas. <br />Assim, você poderá começar a explorar e construir na API do Twitter v2 usando acesso básico.', 'sv-twitter'); ?></p>
    
    <h2><?php echo _e('Passo 2: Salve a chave e os tokens do seu aplicativo e mantenha-os seguros', 'sv-twitter'); ?></h2>
    <p><?php echo _e('Assim que você tiver acesso e tiver criado um projeto e um aplicativo, você poderá encontrar ou gerar as seguintes credenciais dentro do seu aplicativo de desenvolvedor:', 'sv-twitter'); ?></p>

    <ul>
        <li><?php echo _e('<strong>API Key e Secret:</strong> Essencialmente, o nome de usuário e a senha do seu aplicativo. Você usará esses dados para autenticar solicitações que exigem o Contexto do Usuário OAuth 1.0a ou para gerar outros tokens, como tokens de acesso do usuário ou token de acesso do aplicativo.', 'sv-twitter'); ?></li>
        <li><?php echo _e('<strong>Access Token e Secret:</strong> Em geral, os Access Tokens representam o usuário em nome do qual você está fazendo a solicitação. Os tokens que você pode gerar por meio do portal de desenvolvedores representam o usuário que é proprietário do aplicativo. Você usará esses dados para autenticar solicitações que exigem o Contexto do Usuário OAuth 1.0a. Se você deseja fazer solicitações em nome de outro usuário, precisará usar o fluxo de autenticação de 3 etapas do OAuth para que eles autorizem você.', 'sv-twitter'); ?></li>
        <li><?php echo _e('<strong>Client ID e Client Secret:</strong> Essas credenciais são usadas para obter um Access Token do usuário com autenticação OAuth 2.0. Semelhante ao OAuth 1.0a, os Access Tokens do usuário são usados para autenticar solicitações que fornecem informações privadas da conta do usuário ou executam ações em nome de outra conta, mas com escopo detalhado para um controle maior sobre o acesso que o aplicativo do cliente tem ao usuário.', 'sv-twitter'); ?></li>   
        <li><?php echo _e('<strong>App only Access Token:</strong> Você usará esse token ao fazer solicitações para pontos de extremidade que respondem com informações disponíveis publicamente no Twitter.', 'sv-twitter'); ?></li>
    </ul>

    <p><?php echo _e('Já que essas chaves e tokens não expiram, a menos que sejam regenerados, sugerimos que você os salve em um local seguro, como um gerenciador de senhas, assim que receber suas credenciais.', 'sv-twitter'); ?></p>

    <h2><?php echo _e('Passo 3: Copiar as sua chaves geradas', 'sv-twitter'); ?></h2>

    <p>
        <?php echo _e('Agora basta inserir suas chaves nos campos correspondentes na ', 'sv-twitter'); ?>
        <a href="<?php echo admin_url('admin.php?page=' . $settingsPage); ?>" title="<?php echo _e('Configurações', 'sv-twitter'); ?>"><?php echo _e('configuração do plugin', 'sv-twitter'); ?></a> 
        <?php echo _e('e fazer suas customizações.', 'sv-twitter'); ?>
    </p>
</div>