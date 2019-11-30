<?php
    
    // mudando a região para ajustar o tempo do sistema
    date_default_timezone_set('America/Sao_Paulo');

    // função que valida o CPF
    function validaCPF($cpf){
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $digitoA = 0;
        $digitoB = 0;

        for($i = 0, $x = 10; $i <= 8; $i++, $x--){
            $digitoA += $cpf[$i] * $x;
        }

        for($i = 0, $x = 11; $i <= 9; $i++, $x--){
            if(str_repeat($i, 11) == $cpf){
                return;
            }
            $digitoB += $cpf[$i] * $x;
        }

        $somaA = (($digitoA%11) < 2) ? 0 : 11-($digitoA%11);
        $somaB = (($digitoB%11) < 2) ? 0 : 11-($digitoB%11);

        if($somaA != $cpf[9] || $somaB != $cpf[10]){
            return false;
        }else{
            return true;
        }
    }

    // verifica se os campos no formulário estão vazios
    if(!empty($_POST['cNome']) && !empty($_POST['cCPF']) && !empty($_POST['cEmail']) && !empty($_POST['cSenha'])){

        // variáveis que vão receber os valores dos campos do formulário
        $nome = addslashes($_POST['cNome']);
        $cpf = addslashes($_POST['cCPF']);
        $email = addslashes($_POST['cEmail']);
        
        // recebendo a senha do campo no html 'cSenha' e criptografando ela com a função 'md5'
        $senha = addslashes($_POST['cSenha']);
        $senhaCodificada = md5($senha);
        
        // pegando a hora, minutos e segundos
        $timeStamp = date("H:i:s");

        

        // chama a função validar cpf e mostra na tela
        if(validaCPF($cpf)){
            echo 'CPF válido';

            // variável superglobal que contém informações do servidor web
            $useragent = $_SERVER['HTTP_USER_AGENT'];
    
            if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
              $browser_version=$matched[1];
              $browser = 'IE';
            } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
              $browser_version=$matched[1];
              $browser = 'Opera';
            } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
              $browser_version=$matched[1];
              $browser = 'Firefox';
            } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
              $browser_version=$matched[1];
              $browser = 'Chrome';
            } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
              $browser_version=$matched[1];
              $browser = 'Safari';
            } else {
              // browser not recognized!
              $browser_version = 0;
              $browser= 'other';
            }
            $browser .= " ".$browser_version;
            // ------------------------------------------------------------------------------------

            // variável que recebe a conexão e criação do arquivo em disco
            // cria um arquivo com nome do email do cadastro
            $fp = fopen('./'.$cpf.'.txt', 'w+');
            
            // função que escreve no arquivo
            fwrite($fp, "Nome: " . $nome . PHP_EOL);
            fwrite($fp, "CPF: " . $cpf . PHP_EOL);
            fwrite($fp, "Email: " . $email . PHP_EOL);
            fwrite($fp, "Senha: " . $senhaCodificada . PHP_EOL);
            fwrite($fp, "Navegador: " . $browser . PHP_EOL);
            fwrite($fp, "Horário: " . $timeStamp . PHP_EOL);
            

            // função que fecha a conexão com o arquivo em disco
            fclose($fp);

        }else{
            echo 'CPF inválido';
        }
    }

?>
