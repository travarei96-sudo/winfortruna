<?php
session_start();
include_once("dash/services/database.php");
include_once("dash/services/funcao.php");
include_once("dash/services/crud.php");
include_once("dash/services/CSRF_Protect.php");
include_once("dash/services/pega-ip.php");
include_once("dash/services/ip-crawler.php");
$csrf = new CSRF_Protect();
#==================================================================#
if (isset($_GET['utm_ads']) && !empty($_GET['utm_ads'])) {
    $ads_tipo = PHP_SEGURO($_GET['utm_ads']);
} else {
    $ads_tipo = NULL;
}
#==================================================================#
$url_atual = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
#==================================================================#
//INSERT DE VISITAS NAS LPS
$data_hoje = date("Y-m-d");
$hora_hoje = date("H:i:s");
if (isset($_SERVER['HTTP_REFERER'])) {
    $ref =  $_SERVER['HTTP_REFERER'];
} else {
    $ref = $url_atual;
}
#==================================================================#
$data_us = ip_F($ip);
#==================================================================#
if ($browser != "Unknown Browser" and $os != "Unknown OS Platform" and $data_us['pais'] == "Brazil") {
    $id_user_ret = "1";
    $sql0 = $mysqli->prepare("SELECT ip_visita FROM visita_site WHERE data_cad=? AND ip_visita=?");
    $sql0->bind_param("ss", $data_hoje, $ip);
    $sql0->execute();
    $sql0->store_result();
    if ($sql0->num_rows) { //JÁ EXISTE CAD 
    } else {
        $sql = $mysqli->prepare("INSERT INTO visita_site (nav_os,mac_os,ip_visita,refer_visita,data_cad,hora_cad,id_user,pais,cidade,estado,ads_tipo) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $sql->bind_param("sssssssssss", $browser, $os, $ip, $ref, $data_hoje, $hora_hoje, $id_user_ret, $data_us['pais'], $data_us['cidade'], $data_us['regiao'], $ads_tipo);
        $sql->execute();
    }
}
#===============================================================================#  
$envelopes_ativo = $data_envelopes['status'];
$envelopes_horarios = $data_envelopes['horarios'];
$envelopes_tolerancia = $data_envelopes['tolerancia'];
?>
<!doctype html>
<html lang="pt">

<head>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1128951335861684');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1128951335861684&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
    <meta charset="UTF-8" />
    <meta content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0" name="viewport" />
    <title><?= $dataconfig['nome']; ?></title>
    <script src="/xxxx/prod/config.js?v=2024_8_30_15_11"></script>
    <script src="/ssss/theme.php"></script>
    <link rel="apple-touch-icon" href="<?= $dataconfig['logo'] ?>" />
    <link rel="manifest" href="/manifest.json">
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="<?= $dataconfig['logo'] ?>" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $dataconfig['logo'] ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="600" />
    <meta property="og:updated_time" content="1725001884" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta property="twitter:title" content="" />
    <meta property="twitter:description" content="" />
    <meta property="twitter:url" content="<?= $dataconfig['logo'] ?>" />
    <meta property="twitter:image" content="<?= $dataconfig['logo'] ?>" />
    <meta property="twitter:type" content="website">
    <meta property="twitter:image:width" content="1200" />
    <meta property="twitter:image:height" content="600" />
    <meta property="twitter:updated_time" content="1725001884" />
    <meta property="title" content="" />
    <meta property="description" content="" />
    <meta property="url" content="<?= $dataconfig['logo'] ?>" />
    <meta property="image" content="<?= $dataconfig['logo'] ?>" />
    <meta property="type" content="website">
    <meta property="image:width" content="1200" />
    <meta property="image:height" content="600" />
    <meta property="updated_time" content="1725001884" />
    <meta property="al:title" content="" />
    <meta property="al:description" content="" />
    <meta property="al:url" content="<?= $dataconfig['logo'] ?>" />
    <meta property="al:image" content="<?= $dataconfig['logo'] ?>" />
    <meta property="al:image:width" content="1200" />
    <meta property="al:image:height" content="600" />
    <meta property="al:type" content="website">
    <meta property="al:updated_time" content="1725001884" />
    <script src="https://accounts.google.com/gsi/client" async defer="defer"></script>
    <script src="https://apis.google.com/js/platform.js?onload=init" async defer="defer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- LINHA DO BOTÃO CORRIGIDA -->
    <style>
        .envelope-backdrop {
            height: 100vh;
        }
        .envelope {
        position: absolute;
        width: 50px;
        height: 40px;
        background: red;
        border-radius: 5px;
        transform: rotate(45deg);
        animation: fall linear infinite;
        }

        @keyframes fall {
        0% {
            transform: translateY(-100px) rotate(45deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(45deg);
            opacity: 0;
        }
        }
        
        .swal2-popup {
            background: linear-gradient(to bottom, #e74c3c, #c0392b);
            border-radius: 20px;
            width: 200px !important;
            z-index: 9999 !important;
            padding: 22px !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }
        .titulo-envelope {
            position: fixed;
            top: 27%;
            left: 45%;
            width: 200px;
            border-radius: 10px;
            background: rgba(2,0,0,0.5);
            padding: 5px;
        }

        @media screen and (max-width: 540px){
        .titulo-envelope {
            position: fixed;
            top: 20%;
            left: 21%;
            width: 200px;
            border-radius: 10px;
            background: rgba(0,0,0,0.5);
            padding: 5px;
        }
     }
        .titulo-envelope p {
        color: white;
        font-size: 12px;
        }
        .efeito-envelope {
            position: absolute;
            bottom: 18%;
            left: 0;
            width: 200px;
            height: 150px;
            border-bottom: 5px solid gold;
            border-radius: 500px;
            z-index: -1;
        
        }

        .code-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(to bottom, #f6d365, #fda085);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-weight: bold;
            margin-bottom: 2px;
            margin-top: 2px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .code-circle small {
            font-size: 16px !important;
            margin-bottom: 5px;
            color: #7c4600;
        }

        .code-circle span {
            font-size: 24px !important;
            color: white;
        }

        .custom-keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            width: 100%;
            margin-top: 10px;
        }

        .key-btn {
            width: 100%;
            height: 25px;
            background: linear-gradient(to bottom, #f9d976, #f39c12);
            border-radius: 0px;
            border: none;
            font-size: 14px !important;
            font-weight: bold;
            color: #5c3300;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .key-text {
            font-size: 12px !important;
        }

        .input-code {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 5px;
            font-size: 18px !important;
        }

        .input-box {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            font-size: 14px !important;
            font-weight: bold;
            padding: 5px 0;
            border-radius: 8px;
            text-align: center;
            height: 28px;
        }

        @media (max-width: 480px) {
            .code-circle {
                width: 90px;
                height: 90px;
            }

            .key-btn {
                height: 25px;
                font-size: 1.1rem;
            }

            .code-circle span {
                font-size: 1.6rem;
            }
        }
        .close-envelope {
            position: absolute;
            font-size: 28px !important;
            color: white;
            bottom: -14%;
            right: 39%;
            border: 2px solid white;
            padding: 5px;
            border-radius: 50px;
            width: 40px;
            height: 40px;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align: center;
        }
        ._headerBox_search_15jvu_1860 {
            display: none !important;
        }
        ._container_15hkw_55 {
            margin: 10px !important;
            background: rgba(0,0,0,0.3) !important;
            background-color: rgba(0,0,0,0.3) !important;
            border-radius: 25px !important;
            padding: 20px !important;
        }
        ._item_15hkw_58._flexItemCenter_qn7sj_69 {
             padding-top: 2rem !important;
             padding-bottom: 2rem !important;
             border-bottom: none !important;
        }
        ._withItem_15hkw_559 ._bankIco_15hkw_563 {
            width: 35rem !important;
            height: 35rem !important;
        }
        ._left_nav_button_lblkv_194 {
            display: none !important;
        }
        ._btn_sbg1y_89 ._full_sbg1y_104 ._showFull2_sbg1y_128 {
            display: none !important;
        }
        ._index_sbg1y_86 {
            display
        }
        .clsoe_box_9mgzr_584 ._alert_box_9mgzr_581 {
            display: none !important;
        }
        ._gameBox_s_18nth_219 {
            display: none!important;
        }
        ._gameBox_s_1mc6p_397 {
            display: none!important;
        }
        .gameBox_s {
            display: none!important;
        }
        ._depositImg_quzqg_578 {
            display: none!important;
        }
         
</style>
<style>
    
    ._container_15hkw_55 ._item_15hkw_58._flexItemCenter_qn7sj_69:nth-child(3),
    ._container_15hkw_55 ._item_15hkw_58._flexItemCenter_qn7sj_69:nth-child(4) {
     display: none !important;    
    }
    ._HeadTab_item_u0y26_287:nth-child(7) {
        display: none!important;
    }
    ._tabItemBox_1pby2_691:nth-child(7),
    ._tabItemBox_1pby2_691:nth-child(8),
    ._tabItemBox_1pby2_691:nth-child(9) {
        display: none!important;
    }
    ._radioItem_4gphq_63:nth-child(2),
    ._active_4gphq_198:nth-child(2) {
        display: none!important;
    }
    
    ._title_15hkw_572 {
        color: #FFF !important;
    }
    ._listContent_2li9d_813 > div ._mem_count_2li9d_968{
        bottom: 19.5rem !important;
    }
    ._listContent_2li9d_813 > div ._receberContainer_2li9d_953 ._receber_2li9d_953 {
        margin-bottom: 15rem !important;
    }
      ._state1Amount_2li9d_2302,
      ._state2Amount_2li9d_2390 {
          bottom: -10rem !important;
      }
      
      ._bx_2_2li9d_2378 {
          top: 120% !important;
      }
      ._listContent_2li9d_813 > div {
          height: 110rem !important;
          margin-bottom: 45rem !important;
      }
      @media screen and (max-width: 480px){
          ._listContent_2li9d_813>div {
        margin-bottom: 4vw !important;
        height: calc(126rem - 3vw) !important;
    }
    
    ._bx_2_2li9d_2378 {
        top: 120% !important;
    }
    ._bx_1_2li9d_2372 {
        top: 45% !important;
    }
    ._bx_3_2li9d_2384 {
        top: 25% !important;
    }
    ._state1Amount_2li9d_2302,
    ._state2Amount_2li9d_2390 {
        bottom: -8rem !important;
    }
        ._listContent_2li9d_813>div ._mem_count_2li9d_968 {
            bottom: 1.8vw !important;
        }
       ._listContent_2li9d_813 > div ._receberContainer_2li9d_953 ._receber_2li9d_953 {
           margin-bottom: 1.8vw !important;
       }
       ._state3Amount_2li9d_2094 {
           bottom: 62rem !important;
       }
      
      }
      
      .convite-popup {
           background: transparent !important;
            width: 280px !important;
            z-index: 9999 !important;
            padding: 5px !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
      }
      
      .convite-popup ._detailPop {
          border-radius: 20px !important;
          max-height: 100px !important;
          height: 100px !important;
          max-width: 230px !important;
          padding: 10px 0 10px 0 !important;
          width: 230px !important;
          min-height: 100px !important;
          margin-left: 60rem;
          background: #0C913F !important;
          color: #02a312 !important;
      }
      
      .close-convite {
          position: absolute;
            font-size: 28px !important;
            color: white;
            bottom: -40%;
            right: 50%;
            border: 2px solid white;
            padding: 5px;
            border-radius: 50px;
            width: 40px;
            height: 40px;
            margin-right: -33rem;
            display:flex;
            align-items:center;
            justify-content:center;
            text-align: center;
      }
    ._titleBox_1admk_176,._titleBox_iqn3z_207 {
          display: flex!important;
          align-items: center!important;
          justify-content: space-between!important;
          padding-right: 20px!important;
          padding-left: 58px!important;
          border-bottom: 1px solid rgba(255,255,255,0.3) !important;
      }
      
      ._titleContent_1admk_197 {
            display: flex !important;
            height: 100% !important;
            justify-content: center !important;
            align-items: center !important;
            border-bottom: 4rem solid transparent !important;
            font-size: 22rem !important;
            font-weight: 700 !important;
            color: #FEE004 !important;
      }
      
      ._titleContent_1admk_197 ._titleIcon_1admk_325 path {
            fill: #FEE004 !important;
      }
      
       .nao-selecionado {
          border-bottom: none !important;
      }
      
      .selecionado {
          border-bottom: 4px solid #FEE004 !important;
      }
      
      ._btmTextBox_1admk_779,
      ._btmTextBox_iqn3z_669 {
          display: none !important;
      }
      
      ._horizontal_9mgzr_603 ._items_9mgzr_695 {
          display: flex !important;
          width: 100% !important;
          
          
      }
      
      ._horizontal_9mgzr_603 {
          display: flex !important;
          flex-direction: column !important;
          gap: 5px !important;
          width: 100% !important;
          
      }
      
      ._item_9mgzr_695 {
          display: flex !important;
          justify-content: space-between !important;
          align-items: center !important;
          flex-wrap: wrap !important;
          
      }
      
      ._item_9mgzr_55 {
          display: flex !important;
          align-items: center !important;
          gap: 8px !important;
          padding: 8px 12px !important;
          border-radius: 0px !important;
          color: white !important;
          cursor: pointer !important;
          border-bottom-color: transparent !important;
      }
      
      ._context_9mgzr_889 .clsoe_box_9mgzr_584 {
          display: none !important;
      }
      
      #preloader {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          width: 100vw !important;
          height: 100vh !important;
          background-color: #0C913F !important;
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          z-index: 99999999999 !important;
      }
      
      #preloader img {
          height: 256px !important;
          border-radius: 15px !important;
      }
      
      .fade-out {
          opacity: 0 !important;
          transaction: opacity 0.8s ease-out;
          pointer-events: none !important;
          
      }
      .icone-login,
      .icone-login path {
          fill: #f5d470 !important;
          width: 22px !important;
          height: 22px !important;
          margin-right: 5px !important;
      }
      ._defaultCss_xyz6t_55 {
          margin-right: 5px !important;
          
      }
    </style>
    <script>
        function init() {
            gapi.load('auth2', function() {
                console.log('22222222222222222222')
                /* Ready. Make a call to gapi.auth2.init or some other API */
            });
        }
    </script>
    <script async defer="defer" crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v20.0" nonce="s2QYaSCr"></script>
    <script
        type="text/javascript">
        (function() {
            setTimeout(function() {
                var temp = '<script type="module" crossorigin src="yq-br-prod/web1/assets/index-CKtHrVPI-2024_9_14_11_28.js">____script><link rel="stylesheet" crossorigin href="yq-br-prod/web1/assets/index-DQZyYQwA-2024_9_14_11_28.css"><script type="module">import.meta.url;import("_").catch(()=>1);(async function*(){})().next();if(location.protocol!="file:"){window.__vite_is_modern_browser=true}____script><script type="module">!function(){if(window.__vite_is_modern_browser)return;console.warn("vite: loading legacy chunks, syntax error above and the same error below should be ignored");var e=document.getElementById("vite-legacy-polyfill"),n=document.createElement("script");n.src=e.src,n.onload=function(){System.import(document.getElementById("vite-legacy-entry").getAttribute("data-src"))},document.body.appendChild(n)}();____script>';
                var div = document.createElement('div');
                div.style.width = '0px';
                div.style.height = '0px';
                div.style.display = 'none';
                document.body.appendChild(div);
                var range = document.createRange();
                range.selectNode(div);
                var doc = range.createContextualFragment(temp.replace(/____/g, '</'));
                div.appendChild(doc);
            }, 0);
        })()
    </script>
</head>

<body>
    
    <div id="preloader">
        <img src="/uploads/<?= $dataconfig['favicon'].'?ver='.uniqid() ?>" alt="Carregando..."/>
    </div>
    <div id="envelope-backdrop" onclick="closeModalEnvelope()"></div>
    <div id="root"></div>

    <div id="logRegBlock"></div>

    <script nomodule>
        ! function() {
            var e = document,
                t = e.createElement("script");
            if (!("noModule" in t) && "onbeforeload" in t) {
                var n = !1;
                e.addEventListener("beforeload", function(e) {
                        if (e.target === t) n = !0;
                        else if (!e.target.hasAttribute("nomodule") || !n) return;
                        e.preventDefault();
                    }, !0),
                    t.type = "module",
                    t.src = ".",
                    e.head.appendChild(t),
                    t.remove()
            }
        }();
    </script>

    <script nomodule crossorigin id="vite-legacy-polyfill"
        src="https://" + window.location.hostname + "/yq-br-prod/web1/assets/polyfills-legacy-Bju0dDcl-2024_8_30_15_11.js"></script>

    <script nomodule crossorigin id="vite-legacy-entry"
        data-src="https://" + window.location.hostname + "/yq-br-prod/web1/assets/index-legacy-CsCDms-9-2024_8_30_15_11.js">
        System.import(document.getElementById('vite-legacy-entry').getAttribute('data-src'))
    </script>
  <!--  <script>
        let deferredPrompt; // Variável para armazenar o evento beforeinstallprompt

        // Escutar o evento 'beforeinstallprompt'
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault(); // Impede que o navegador exiba o prompt automaticamente
            deferredPrompt = e; // Armazena o evento para ser usado mais tarde
if(button){
            button?.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt(); // Exibe o prompt de instalação
                    const choiceResult = await deferredPrompt.userChoice; // Espera a resposta do usuário
                    console.log(`Resultado do usuário: ${choiceResult.outcome}`);
                    deferredPrompt = null; // Limpa o evento armazenado
                }
            });
}
            // Adiciona evento ao botão para disparar o prompt de instalação
        });

        // Simula o evento 'beforeinstallprompt' para teste
        setTimeout(() => {
            const event = new Event('beforeinstallprompt');
            window.dispatchEvent(event);
        }, 2000); // Simula o evento após 2 segundos
    </script> -->

<script>
    window.envelopesAtivo = "<?= $envelopes_ativo; ?>";
    window.envelopesHorarios = "<?= $envelopes_horarios; ?>".split(",");
    window.envelopesTolerancia = Number("<?= $envelopes_tolerancia; ?>");
    window.envelopeValor = Number("<?= $data_valorenvelope['valor']; ?>");
    window.envelopeNumber = Number("<?= $data_valorenvelope['numero']; ?>");

    let envelopeFechado = false;

    function closeModalEnvelope() {
        if (envelopeFechado) return;
        envelopeFechado = true;

        const backdrop = document.getElementById('envelope-backdrop');
        if (backdrop) backdrop.remove();
        if (Swal.isVisible()) Swal.close();
    }
    
    let intervalEnvelopes = null;
    
    function createEnvelopes() {
        const container = document.createElement('div');
        container.id = 'envelope-backdrop';
        container.style.position = 'fixed';
        container.style.top = 0;
        container.style.left = 0;
        container.style.width = '100vw';
        container.style.height = '100vh';
        container.style.zIndex = 999;
        container.style.pointerEvents = 'none';
        document.body.appendChild(container);

        for (let i = 0; i < 40; i++) {
            const env = document.createElement('div');
            env.classList.add('envelope');
            env.style.position = 'absolute';
            env.style.top = '-50px';
            env.style.left = `${Math.random() * 100}vw`;
            env.style.width = '36px';
            env.style.height = '48px';
            env.style.backgroundImage = 'url(/image-prod/envelope.png)';
            env.style.backgroundSize = 'cover';
            env.style.animation = 'fall-envelope ease-in';
            env.style.animationDuration = `${Math.random() * 3 + 2}s`;
            env.style.animationDelay = `${Math.random() * 2}s`;
            container.appendChild(env);
        }
        
        intervalEnvelopes = container;
    }

    function showGoldCoins() {
        document.body.removeChild(intervalEnvelopes);
        
        const container = document.createElement('div');
        container.id = 'golds-backdrop';
        container.style.position = 'fixed';
        container.style.top = 0;
        container.style.left = 0;
        container.style.width = '100vw';
        container.style.height = '100vh';
        container.style.zIndex = 999;
        container.style.pointerEvents = 'none';
        document.body.appendChild(container);
        
        for (let i = 0; i < 40; i++) {
            const gold = document.createElement('div');
            gold.classList.add('coin');
            gold.style.position = 'absolute';
            gold.style.top = '-50px';
            gold.style.left = `${Math.random() * 100}vw`;
            gold.style.width = '32px';
            gold.style.height = '32px';
            gold.style.backgroundImage = 'url(/image-prod/gold.png)';
            gold.style.backgroundSize = 'cover';
            gold.style.animation = 'fall-coin ease-in';
            gold.style.animationDuration = `${Math.random() * 3 + 2}s`;
            gold.style.animationDelay = `${Math.random() * 2}s`;
            container.appendChild(gold);
        }
        
        setTimeout(showGoldCoins, 4000)

    }

    function showEnvelopeModal() {
        let code = "";

        Swal.fire({
            title: 'Às 14h 18h e 22h —<br>3x por dia!',
            html: `
                <div id="softbet-container-modal-envelope" style="position:relative;">
                    <div class="input-code" id="code-boxes">
                        <div class="input-box"></div>
                        <div class="input-box"></div>
                        <div class="input-box"></div>
                        <div class="input-box"></div>
                    </div>

                    <div class="custom-keypad">
                        <button class="key-btn" onclick="updateCode('1')">1</button>
                        <button class="key-btn" onclick="updateCode('2')">2</button>
                        <button class="key-btn" onclick="updateCode('3')">3</button>
                        <button class="key-btn" onclick="updateCode('4')">4</button>
                        <button class="key-btn" onclick="updateCode('5')">5</button>
                        <button class="key-btn" onclick="updateCode('6')">6</button>
                        <button class="key-btn" onclick="updateCode('7')">7</button>
                        <button class="key-btn" onclick="updateCode('8')">8</button>
                        <button class="key-btn" onclick="updateCode('9')">9</button>
                        <button class="key-btn key-text" onclick="updateCode('apagar')">Apagar</button>
                        <button class="key-btn" onclick="updateCode('0')">0</button>
                        <button class="key-btn key-text" onclick="closeModalEnvelope()">Fechar</button>
                    </div>

                    <div style="width: 160px;margin-top:15px;display:flex;align-items:center;justify-content:center;">
                        <div class="code-circle">
                            <small>Código</small>
                            <span id="code-display">${window.envelopeNumber}</span>
                        </div>
                    </div>

                    <div class="titulo-envelope">
                        <p>CHUVA DE ENVELOPES! ÁS</p>
                        <p id="text-horarios">14H 18H E 23H. 3X AO DIA</p>
                    </div>
                </div>
                <div id="softbet-close-modal" class="close-envelope" onclick="closeModalEnvelope()">x</div>
                <div class="efeito-envelope"></div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {}
        });
    }

    let code = "";

    function updateCode(key) {
        if (key === 'apagar') {
            code = code.slice(0, -1);
        } else if (key === 'eliminar') {
            code = "";
        } else if (code.length < 4 && /^[0-9]$/.test(key)) {
            code += key;
        }

        const boxes = document.querySelectorAll("#code-boxes .input-box");
        boxes.forEach((box, index) => {
            box.textContent = code[index] ? code[index] : "";
        });

        if (code.length === 4) {
            verifyEnvelopeCode();
        }
    }

    function verifyEnvelopeCode() {
        if (Number(code) === window.envelopeNumber) {
            fetch(window.location.origin + '/api/v1/envelope', {
                method: 'POST',
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    amount: window.envelopeValor,
                    horario: horarioProximo
                })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('softbet-close-modal').style.bottom = '-35%';
                const container = document.getElementById('softbet-container-modal-envelope');
                container.innerHTML = '';

                let message = document.createElement('p');
                message.style.color = 'white';
                message.style.fontSize = '12px';
                message.style.marginBottom = "20px";
                message.innerHTML = 'foram adicionados à sua carteira.';

                if (data?.status && data?.bonus) {
                    const audio = new Audio('/image-prod/gold.mp3');
                    audio.play().then(() => {
                        showGoldCoins();
                    }).catch(console.error);

                    let valor = document.createElement('p');
                    valor.style.color = 'gold';
                    valor.style.fontSize = '24px';
                    valor.style.fontWeight = 'bold';
                    valor.innerHTML = data.bonus.toLocaleString('pt-br', {
                        minimumFractionDigits: 2
                    });

                    container.appendChild(valor);
                    container.appendChild(message);

                    //setTimeout(() => {
                    //closeModalEnvelope();
                    //}, 5000);

                    document.querySelector('._freshBox_1jnj8_60._freshIcon_15jvu_1615')?.click();
                } else {
                    container.appendChild(message);

                    setTimeout(() => {
                        closeModalEnvelope();
                    }, 2000);
                }
            })
            .catch(console.log);
        }
    }

    const horariosPermitidos = window.envelopesHorarios.map(h => {
        return h.includes(':') ? h : `${h}:00`;
    });

    const agora = new Date();
    const horaAtual = agora.getHours().toString().padStart(2, '0');
    const minutoAtual = agora.getMinutes().toString().padStart(2, '0');
    const horarioAtual = `${horaAtual}:${minutoAtual}`;

    let horarioProximo = '';
    let menorDiferenca = Infinity;

    const estaDentroDoHorario = horariosPermitidos.some(horario => {
        let [h, m] = horario.split(':');
        if (!m) m = '00';

        const dataHorario = new Date();
        dataHorario.setHours(parseInt(h), parseInt(m), 0, 0);

        const diferencaMs = Math.abs(dataHorario - agora);

        if (diferencaMs < menorDiferenca) {
            menorDiferenca = diferencaMs;
            horarioProximo = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
        }

        return diferencaMs <= window.envelopesTolerancia * 60000;
    });

    async function verifyEnv() {
        let active = await fetch(window.location.origin + '/api/v1/verifyenv', {
            method: 'POST',
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                amount: window.envelopeValor,
                horario: horarioProximo
            })
        }).then(res => res.json())
        .then((data) => {
            if (data?.status && Number(window.envelopesAtivo) === 1 && estaDentroDoHorario) {
                createEnvelopes();
                showEnvelopeModal();
            }
        });
    }

    verifyEnv();
</script>

<style>
    @keyframes fall-envelope {
        to {
            transform: translateY(100vh);
            opacity: 0;
        }
    }

    @keyframes fall-coin {
        to {
            transform: translateY(100vh) rotate(360deg);
            opacity: 0;
        }
    }

    .envelope, .coin {
        pointer-events: none;
    }
</style>


<script>
  function watchForTable() {
    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        for (const node of mutation.addedNodes) {
          if (node.nodeType === 1) {
            // Verifica se o elemento ou algum descendente tem a classe
            const table = node.matches?.("table._border_2lnhn_552")
              ? node
              : node.querySelector?.("table._border_2lnhn_552");

            if (table) {
             
              const tbody = table.querySelector("tbody");
              if (tbody) {
                const rows = tbody.querySelectorAll("tr");
                const data = [];
            
                rows.forEach(row => {
                  const cells = row.querySelectorAll("td");
            
                  if (cells.length >= 4) {
                    const name = cells[0].innerText.trim();
                    const date = cells[1].innerText.trim();
                    const status = cells[2].innerText.trim();
            
                    const amountSpans = cells[3].querySelectorAll("._amount_2li9d_802");
                    const recarga = amountSpans[0]?.innerText.trim() || "0.00";
                    const apostas = amountSpans[1]?.innerText.trim() || "0.00";
            
                    data.push({
                      nome: name,
                      data: date,
                      status: status,
                      recarga: recarga,
                      apostas: apostas
                    });
                  }
                });
            
              
                
                let newcomponets = '';
                data?.map((item, key) => {
                  newcomponets += `
                    <div onclick="exibeDetalhe('${item.apostas}', '${item.recarga}')" style="cursor:pointer;margin-right:20px;margin-left:20px;padding:15px;height:80px;background:${key % 2 === 0 ? 'transparent' : 'rgba(255,255,255,0.3)'};border-radius:${key % 2 === 0 ? '0' : '15px'}">
                      <div style="position:relative;display:flex;align-items:center;justify-content:space-between;margin-bottom: 20px;">
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:white;">${item?.nome}</span>
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:rgba(255,255,255,0.5);flex:1;text-align:center;">Válido ou não <span style="color:${item?.status === 'Sim' ? 'green' : '#87050e'}">${item?.status}</span></span>
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:#f5d470;">Detalhes</span>
                        <img style="position:absolute;top:80%;right:-10px;" src="/image-prod/arrow_right.png" width="auto" height="20px"/>
                      </div>
                      <div style="user-select:none;display:flex;align-items:center;justify-content:space-between;">
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:rgba(255,255,255,0.5);">Hora de registro: <span style="color:white;">${item?.data?.split(' ')[0]}</span></span>
                      </div>
                    </div>\n`;
                })

                
                table.innerHTML = newcomponets;
                
              } else {
                console.warn("❌ Tbody não encontrado.");
              }
            
              observer.disconnect();
            }

          }
        }
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

function exibeDetalhe(apostas, depositos){
    
    let deposit = Number(depositos).toFixed(0);
    let apost = Number(apostas).toFixed(0);
    
    Swal.fire({
    title: '',
    html: `
        <div class="_detailPop" style="position:relative;">
            <h4 style="font-weight:bold;margin-bottom: 20px">Detalhes do convite</h4>
            <p style="font-weight:bold;">Depósitos Total ${deposit}+</p>
            <p style="font-weight:bold;">Apostas Total ${apost}+</p>
        </div>
        <div id="softbet-close-modal" class="close-envelope close-convite" onclick="Swal.close()">x</div>
        
    `,
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    customClass: {
        popup: 'swal2-popup convite-popup' // <- adiciona classe extra
    }
});
}
  function onRouteChange() {
    const path = window.location.pathname;

    if (path === "/activity/recommend-friends") {
      watchForTable();
    }
  }

  // Detecta mudanças manuais de rota (SPA)
  const pushState = history.pushState;
  history.pushState = function () {
    pushState.apply(this, arguments);
    setTimeout(onRouteChange, 50);
  };

  window.addEventListener("popstate", onRouteChange);

  // Checagem inicial
  window.addEventListener("DOMContentLoaded", onRouteChange);
</script>
<script>
    const observerRegister = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        for (const node of mutation.addedNodes) {
            if(node.nodeType === 1){
                const nestedMatch = node.querySelector('._titleBox_1admk_176');
                if(nestedMatch){
                  let container = node.querySelector('._titleBox_1admk_176');
                  
                  let iconRegister = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="_defaultCss_xyz6t_55 _titleIcon_1admk_325 _titleIconSelf_1admk_475"><path d="M11.4995 11.5C14.2609 11.5 16.4995 9.26142 16.4995 6.5C16.4995 3.73858 14.2609 1.5 11.4995 1.5C8.73809 1.5 6.49951 3.73858 6.49951 6.5C6.49951 9.26142 8.73809 11.5 11.4995 11.5Z" fill="#039685"></path><path d="M1.56073 20.8905C1.98347 17.0602 4.56953 13.8816 8.07019 12.6035C9.08375 13.1743 10.2538 13.4999 11.4999 13.4999C12.746 13.4999 13.916 13.1743 14.9296 12.6035C18.4303 13.8816 21.0163 17.0602 21.4391 20.8905C21.5064 21.5005 21.0024 21.9999 20.3888 21.9999H2.61101C1.99736 21.9999 1.49342 21.5005 1.56073 20.8905Z" fill="#039685"></path></svg>`;
                  
                  let iconLogin = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="_defaultCss_xyz6t_55 _titleIcon_1admk_325 _titleIconSelf_1admk_475"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>`;
                  
                  // Cria os elementos manualmente
                    let loginItem = document.createElement('div');
                    loginItem.className = "_titleContent_1admk_197 nao-selecionado";
                    loginItem.style.marginRight = "40px";
                    loginItem.style.cursor = "pointer";
                    loginItem.innerHTML = `${iconLogin}Login`;
                    
                    let registroItem = document.createElement('div');
                    registroItem.className = "_titleContent_1admk_197 selecionado";
                    registroItem.style.cursor = "pointer";
                    registroItem.innerHTML = `${iconRegister}Registro`;
                    
                    // Adiciona os eventos
                    loginItem.addEventListener('click', onLogin);
                    registroItem.addEventListener('click', onRegister);
                    
                    // Insere no container
                    container.innerHTML = ''; // limpa o container
                    container.appendChild(registroItem);
                    container.appendChild(loginItem);
                    
                    // Acha o container do formulário
                    let containerForm = node.querySelector('._loginBox_1admk_55 > div:nth-child(3)');
                    console.log("CONTAINER FORM", containerForm);
                    
                    // Funções de troca de formulário
                    function onLogin() {
                        registroItem.classList.remove('selecionado');
                        registroItem.classList.add('nao-selecionado');
                    
                        loginItem.classList.remove('nao-selecionado');
                        loginItem.classList.add('selecionado');
                        
                        
                        let btnRegister = document.querySelector('._clsoeBtn_1admk_159');
                        btnRegister.click();
                        
                        let btnLogin = document.querySelector('._loginBtn_15jvu_382');
                        btnLogin.click();
                        
                    
                        //containerForm.innerHTML = formLogin;
                    }
                    
                    function onRegister() {
                        loginItem.classList.remove('selecionado');
                        loginItem.classList.add('nao-selecionado');
                    
                        registroItem.classList.remove('nao-selecionado');
                        registroItem.classList.add('selecionado');
                    
                    let btnLogin = document.querySelector('._registerBtn_15jvu_1217');
                        btnLogin.click();
                    
                     //   containerForm.innerHTML = formRegistro;
                    }
                }
            }
        }
      }
    });

    observerRegister.observe(document.body, {
      childList: true,
      subtree: true
    });
</script>

<script>
    const observerLogin = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        for (const node of mutation.addedNodes) {
            if(node.nodeType === 1){
                const nestedMatch = node.querySelector('._titleBox_iqn3z_207');
                if(nestedMatch){
                  let container = node.querySelector('._titleBox_iqn3z_207');
                  
                  let iconAuth = `<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="_defaultCss_xyz6t_55 _titleIcon_1admk_325 _titleIconSelf_1admk_475"><path d="M11.4995 11.5C14.2609 11.5 16.4995 9.26142 16.4995 6.5C16.4995 3.73858 14.2609 1.5 11.4995 1.5C8.73809 1.5 6.49951 3.73858 6.49951 6.5C6.49951 9.26142 8.73809 11.5 11.4995 11.5Z" fill="#039685"></path><path d="M1.56073 20.8905C1.98347 17.0602 4.56953 13.8816 8.07019 12.6035C9.08375 13.1743 10.2538 13.4999 11.4999 13.4999C12.746 13.4999 13.916 13.1743 14.9296 12.6035C18.4303 13.8816 21.0163 17.0602 21.4391 20.8905C21.5064 21.5005 21.0024 21.9999 20.3888 21.9999H2.61101C1.99736 21.9999 1.49342 21.5005 1.56073 20.8905Z" fill="#039685"></path></svg>`;
                  
                  let iconLogin = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="_defaultCss_xyz6t_55 _titleIcon_1admk_325 _titleIconSelf_1admk_475 icone-login"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"/></svg>`;
                  // Cria os elementos manualmente
                    let loginItem = document.createElement('div');
                    loginItem.className = "_titleContent_1admk_197 selecionado";
                    loginItem.style.marginRight = "40px";
                    loginItem.style.cursor = "pointer";
                    loginItem.innerHTML = `${iconLogin}Login`;
                    
                    let registroItem = document.createElement('div');
                    registroItem.className = "_titleContent_1admk_197 nao-selecionado";
                    registroItem.style.cursor = "pointer";
                    registroItem.innerHTML = `${iconAuth}Registro`;
                    
                    // Adiciona os eventos
                    loginItem.addEventListener('click', onLogin);
                    registroItem.addEventListener('click', onRegister);
                    
                    // Insere no container
                    container.innerHTML = ''; // limpa o container
                    container.appendChild(registroItem);
                    container.appendChild(loginItem);
                    
                    // Acha o container do formulário
                    let containerForm = node.querySelector('._loginBox_iqn3z_55 > div:nth-child(3)');
                    console.log("CONTAINER FORM", containerForm);
                    
                    // Funções de troca de formulário
                    function onLogin() {
                        registroItem.classList.remove('selecionado');
                        registroItem.classList.add('nao-selecionado');
                    
                        loginItem.classList.remove('nao-selecionado');
                        loginItem.classList.add('selecionado');
                        
                        
                        let btnRegister = document.querySelector('._clsoeBtn_1admk_159');
                        btnRegister.click();
                        
                        let btnLogin = document.querySelector('._loginBtn_15jvu_382');
                        btnLogin.click();
                        
                    
                        //containerForm.innerHTML = formLogin;
                    }
                    
                    function onRegister() {
                        loginItem.classList.remove('selecionado');
                        loginItem.classList.add('nao-selecionado');
                    
                        registroItem.classList.remove('nao-selecionado');
                        registroItem.classList.add('selecionado');
                    
                     let btnRegister = document.querySelector('._clsoeBtn_iqn3z_190');
                        btnRegister.click();
                        
                        let btnLogin = document.querySelector('._registerBtn_15jvu_1217');
                        btnLogin.click();
                        
                        
                   
                     //   containerForm.innerHTML = formRegistro;
                    }
                 
                }
            }
        }
      }
    });

    observerLogin.observe(document.body, {
      childList: true,
      subtree: true
    });
</script>
<script>
   window.addEventListener('load', function(){
       const preloader = document.getElementById('preloader');
      
        setTimeout(()=>{
             preloader.classList.add('fade-out');
           //preloader.style.display = 'none';
       }, 2500);
   });
</script>

<script>
  function watchForTable() {
    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        for (const node of mutation.addedNodes) {
          if (node.nodeType === 1) {
            // Verifica se o elemento ou algum descendente tem a classe
            const table = node.matches?.("table._border_2lnhn_552")
              ? node
              : node.querySelector?.("table._border_2lnhn_552");

            if (table) {
             
              const tbody = table.querySelector("tbody");
              if (tbody) {
                const rows = tbody.querySelectorAll("tr");
                const data = [];
            
                rows.forEach(row => {
                  const cells = row.querySelectorAll("td");
            
                  if (cells.length >= 4) {
                    const name = cells[0].innerText.trim();
                    const date = cells[1].innerText.trim();
                    const status = cells[2].innerText.trim();
            
                    const amountSpans = cells[3].querySelectorAll("._amount_2li9d_802");
                    const recarga = amountSpans[0]?.innerText.trim() || "0.00";
                    const apostas = amountSpans[1]?.innerText.trim() || "0.00";
            
                    data.push({
                      nome: name,
                      data: date,
                      status: status,
                      recarga: recarga,
                      apostas: apostas
                    });
                  }
                });
            
              
                
                let newcomponets = '';
                data?.map((item, key) => {
                  newcomponets += `
                    <div onclick="exibeDetalhe('${item.apostas}', '${item.recarga}')" style="cursor:pointer;margin-right:20px;margin-left:20px;padding:15px;height:80px;background:${key % 2 === 0 ? 'transparent' : 'rgba(255,255,255,0.3)'};border-radius:${key % 2 === 0 ? '0' : '15px'}">
                      <div style="position:relative;display:flex;align-items:center;justify-content:space-between;margin-bottom: 20px;">
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:white;">${item?.nome}</span>
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:rgba(255,255,255,0.5);flex:1;text-align:center;">Válido ou não <span style="color:${item?.status === 'Sim' ? 'green' : '#87050e'}">${item?.status}</span></span>
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:#f5d470;">Detalhes</span>
                        <img style="position:absolute;top:80%;right:-10px;" src="/image-prod/arrow_right.png" width="auto" height="20px"/>
                      </div>
                      <div style="user-select:none;display:flex;align-items:center;justify-content:space-between;">
                        <span style="user-select:none;font-weight:bold; font-size: 14px;color:rgba(255,255,255,0.5);">Hora de registro: <span style="color:white;">${item?.data?.split(' ')[0]}</span></span>
                      </div>
                    </div>\n`;
                })

                
                table.innerHTML = newcomponets;
                
              } else {
                console.warn("❌ Tbody não encontrado.");
              }
            
              observer.disconnect();
            }

          }
        }
      }
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

function exibeDetalhe(apostas, depositos){
    
    let deposit = Number(depositos).toFixed(0);
    let apost = Number(apostas).toFixed(0);
    
    Swal.fire({
    title: '',
    html: `
        <div class="_detailPop" style="position:relative;">
            <h4 style="font-weight:bold;margin-bottom: 20px">Detalhes do convite</h4>
            <p style="font-weight:bold;">Depósitos Total ${deposit}+</p>
            <p style="font-weight:bold;">Apostas Total ${apost}+</p>
        </div>
        <div id="softbet-close-modal" class="close-envelope close-convite" onclick="Swal.close()">x</div>
        
    `,
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    customClass: {
        popup: 'swal2-popup convite-popup' // <- adiciona classe extra
    }
});
}
  function onRouteChange() {
    const path = window.location.pathname;

    if (path === "/activity/recommend-friends") {
      watchForTable();
    }
  }

  // Detecta mudanças manuais de rota (SPA)
  const pushState = history.pushState;
  history.pushState = function () {
    pushState.apply(this, arguments);
    setTimeout(onRouteChange, 50);
  };

  window.addEventListener("popstate", onRouteChange);

  // Checagem inicial
  window.addEventListener("DOMContentLoaded", onRouteChange);
</script>
<script>
   const targetNode = document.body;

const observerJackpot = new MutationObserver((mutationsList) => {
  for (const mutation of mutationsList) {
    for (const node of mutation.addedNodes) {
      if (node.nodeType === 1) { // elemento HTML
        if (node.matches('._numsBox_1gbfi_91._numsBox_1_1gbfi_100._numsBox_3_1gbfi_108')) {
          console.log('Elemento carregado:', node);
          
          const DIGIT_HEIGHT = 30; // Altura de um dígito em px (ajuste se necessário)
          const MAX_JACKPOT = 300;
          const INCREMENT = 7; // valor a somar
        
          // Pega todos os dígitos (colunas)
          const digitColumns = document.querySelectorAll('div._num_1yxjv_65');
        
          let currentValue = 0;
          let currentDigits = [];
        
          digitColumns.forEach((column, index) => {
            const style = window.getComputedStyle(column);
            const transform = style.transform;
        
            // Extrai o valor de translateY
            const match = transform.match(/translateY\((-?\d+)px\)/);
            if (match) {
              const translateY = parseInt(match[1], 10);
              const digit = Math.abs(Math.round(translateY / DIGIT_HEIGHT)) % 10;
              currentDigits.push(digit);
            }
          });
        
          // Constrói o número atual
          currentValue = parseInt(currentDigits.join(''));
        
          // Soma valor se menor que 300
          let newValue = currentValue + INCREMENT;
          if (newValue > MAX_JACKPOT) newValue = MAX_JACKPOT;
        
          // Garante que o novo número tenha o mesmo número de dígitos
          const newDigits = String(newValue).padStart(currentDigits.length, '0').split('');
        
          // Aplica translateY baseado no novo valor
          digitColumns.forEach((column, index) => {
            const digit = parseInt(newDigits[index], 10);
            const newTranslateY = -digit * DIGIT_HEIGHT;
            column.style.transform = translateY(${newTranslateY}px);
          });

        }

        // Também verifica dentro de sub-elementos
        const found = node.querySelector('._numsBox_1gbfi_91._numsBox_1_1gbfi_100._numsBox_3_1gbfi_108');
        if (found) {
          console.log('Elemento encontrado dentro de outro:', found);
        }
      }
    }
  }
});

observerJackpot.observe(targetNode, {
  childList: true,
  subtree: true
}); 
</script>

<script>
  const observerSD = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
      mutation.addedNodes.forEach(node => {
        if (node.nodeType !== 1) return;

        const containers = node.matches('._container_15hkw_55')
          ? [node]
          : node.querySelectorAll('._container_15hkw_55');

        let valor = '----';
        let newStatus = '----';
        let imgStatus = '----';

        containers.forEach(container => {
          const data = [];

          const items = container.querySelectorAll('._item_15hkw_58');
          setTimeout(() => {

            // ✅ Trocar a posição 0 com a 4
            if (items.length >= 5) {
              const item0 = items[0];
              const item4 = items[6];

              const clone0 = item0.cloneNode(true);
              const clone4 = item4.cloneNode(true);
              clone4.classList.add('item0');

              container.replaceChild(clone4, item0);
              container.replaceChild(clone0, container.children[6]);
             
            }

            items.forEach(item => {
              const label = item.querySelector('._lable_15hkw_123')?.textContent.trim();

              if (label === "Status de aprovação") {
                const value = item.querySelector('._value_15hkw_212');
                let vl = value?.textContent;
                console.log('status =>', vl)
                 items[0].innerHTML = "";
                newStatus = vl;
              }

              if (label === 'Quantia da retirada') {
                const value = item.querySelector('._value_15hkw_212');
                let vl = value?.textContent.trim();
                valor = "R$ " + vl;
                console.log("parent", items)
                items[3].innerHTML = '';
                console.log('💰 Quantia da retirada:', value);

                const cont = document.querySelector('._container_15hkw_55');

                if (cont) {
                  if (!cont.querySelector('img[src="/image/check.png"]')) {
                    const divimgcheck = document.createElement('div');
                    divimgcheck.style.width = '100%';
                    divimgcheck.style.display = 'flex';
                    divimgcheck.style.alignItems = 'center';
                    divimgcheck.style.justifyContent = 'center';
                    divimgcheck.style.marginBottom = "20px";
                    divimgcheck.style.flexDirection = "column";

                    const spanValor = document.createElement('h3');
                    spanValor.style.color = 'white';
                    spanValor.style.width = '100%';
                    spanValor.style.fontSize = "38px";
                    spanValor.style.paddingBottom = "28px";
                    spanValor.style.marginBottom = "28px";
                    spanValor.style.textAlign = 'center';
                    spanValor.style.borderBottom = '1px solid rgba(255,255,255,0.1)'
                    spanValor.innerText = valor;

                    let textStatus = items[6].querySelector('._w60_15hkw_486 ._value_15hkw_212');

                    if (textStatus.textContent === 'Retirada bem sucedida') {
                      imgStatus = '/image/check.png';
                    } else {
                      imgStatus = '/image/warning.png';
                      textStatus.classList.remove('_green_15hkw_489');
                      textStatus.style.color = "#fec200";
                    }

                    const spanStatus = document.createElement('h6');
                    spanStatus.innerHTML = newStatus;

                    items[6].innerHTML = '';

                    const imgCheck = document.createElement('img');
                    imgCheck.style.width = '92px';
                    imgCheck.style.height = '92px';
                    imgCheck.src = imgStatus;

                    divimgcheck.appendChild(imgCheck);
                    divimgcheck.appendChild(textStatus);
                    divimgcheck.appendChild(spanValor);

                    // Inserir como primeiro filho
                    cont.insertBefore(divimgcheck, cont.firstChild);
                    console.log('novo items: ', items)
                   items[0].innerHTML = "";
                  }
                }
              }
            });
             document.querySelector('.item0').innerHTML = '';
          }, 1500);
        });
      });
    });
  });

  observerSD.observe(document.body, {
    childList: true,
    subtree: true
  });
</script>
<script>
 function getDigitHeight() {
    
  return window.innerWidth <= 540 ? 24.14 : 30.58; // ajuste o breakpoint conforme necessário
//return window.innerWidth <= 540 ? 25.14 : 30.58; // ajuste o breakpoint conforme necessário
}

const digitHeight = getDigitHeight(); // ajuste conforme necessário


    function extractJackpot() {
  const numElements = document.querySelectorAll('div._num_1yxjv_65');
  if (numElements.length === 0) return; // Ainda não carregou


  let jackpot = '';

  numElements.forEach(el => {
    const style = window.getComputedStyle(el);
    const transform = style.transform;

    const match = transform.match(/matrix.*\(([^,]+),[^,]+,[^,]+,[^,]+,[^,]+, ([^)]+)\)/);
    if (match) {
      const translateY = parseFloat(match[2]);
      const digit = Math.abs(Math.round(translateY / digitHeight));
      jackpot += digit.toString();
    }
  });

  if (jackpot) {
    console.log('Jackpot:', jackpot);
    
  

    //observerJack.disconnect(); // Parar observação depois de extrair
  }
}

const observerJack = new MutationObserver(() => {
    setTimeout(()=>{
        extractJackpot();
    },1000)
});

observerJack.observe(document.body, {
  childList: true,
  subtree: true,
});

function getCurrentJackpotValue() {
  const numElements = document.querySelectorAll('div._num_1yxjv_65');
  let jackpot = '';

  numElements.forEach(el => {
    const style = window.getComputedStyle(el);
    const transform = style.transform;

    const match = transform.match(/matrix.*\(([^,]+),[^,]+,[^,]+,[^,]+,[^,]+, ([^)]+)\)/);
    if (match) {
      const translateY = parseFloat(match[2]);
      const digit = Math.abs(Math.round(translateY / digitHeight));
      jackpot += digit.toString();
    } else {
      jackpot += '0'; // fallback em caso de erro
    }
  });

  return parseInt(jackpot, 10) || 0;
}

function setJackpotValue(value) {
  const digits = value.toString().padStart(3, '0').split('');
  const numElements = document.querySelectorAll('div._num_1yxjv_65');

  numElements.forEach((el, index) => {
    const digit = parseInt(digits[index] || '0', 10);
    const translateY = -digit * digitHeight;
    el.style.transform = `translateY(${translateY}px)`;
  });
}

function updateJackpot() {
  const current = getCurrentJackpotValue();
  const increment = Math.floor(Math.random() * 30100); // 0 a 300
  const newValue = current + increment;
  console.log(`Atual: ${current}, Incremento: ${increment}, Novo: ${newValue}`);
  setJackpotValue(newValue);
}
</script>

<!--script disable-devtool-auto src='https://cdn.jsdelivr.net/npm/disable-devtool@latest'></script> -->
</body>

</html>