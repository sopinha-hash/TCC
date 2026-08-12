<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Dashboard | Sistema de Alerta de Faltas</title>


    <link rel="stylesheet" href="assets/css/global.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>


<body>


    <header class="dashboard-header">


        <div class="dashboard-header__content">


            <img
                src="assets/img/logo-escola.png"
                alt="Logo da escola"
                class="dashboard-header__logo"
            >


            <div class="dashboard-header__title">
                <span>Sistema de Alerta</span>
                <span>de <strong>Faltas Escolares</strong></span>
            </div>


            <!-- Elementos decorativos -->
            <div class="dashboard-header__decorations" aria-hidden="true">


                <!-- Usuários -->
                <svg class="header-icon header-icon--users"
                     viewBox="0 0 24 24"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 21V19C16 16.79 14.21 15 12 15H6C3.79 15 2 16.79 2 19V21"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                    <circle cx="9" cy="7" r="3.5"
                            stroke="currentColor"
                            stroke-width="1.6"/>
                    <path d="M22 21V19C22 17.13 20.72 15.56 19 15.1"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                    <path d="M16 3.13C17.73 3.57 19 5.14 19 7C19 8.86 17.73 10.43 16 10.87"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                </svg>




                <!-- Sino -->
                <svg class="header-icon header-icon--bell"
                     viewBox="0 0 24 24"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 8C18 4.69 15.76 2 12 2C8.24 2 6 4.69 6 8C6 13 4 14 4 16H20C20 14 18 13 18 8Z"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linejoin="round"/>
                    <path d="M9.5 20C10.1 21.2 11 22 12 22C13 22 13.9 21.2 14.5 20"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                </svg>




                <!-- Documento -->
                <svg class="header-icon header-icon--file"
                     viewBox="0 0 24 24"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linejoin="round"/>
                    <path d="M14 2V8H20"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linejoin="round"/>
                    <path d="M8 13H16"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                    <path d="M8 17H14"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                </svg>




                <!-- E-mail -->
                <svg class="header-icon header-icon--mail"
                     viewBox="0 0 24 24"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="5" width="18" height="14" rx="2"
                          stroke="currentColor"
                          stroke-width="1.6"/>
                    <path d="M3 7L12 13L21 7"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"
                          stroke-linejoin="round"/>
                </svg>




                <!-- Alerta -->
                <svg class="header-icon header-icon--alert"
                     viewBox="0 0 24 24"
                     fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.3 4.2L2.7 17.5C2.05 18.65 2.88 20 4.2 20H19.8C21.12 20 21.95 18.65 21.3 17.5L13.7 4.2C13 2.98 11 2.98 10.3 4.2Z"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linejoin="round"/>
                    <path d="M12 9V13"
                          stroke="currentColor"
                          stroke-width="1.6"
                          stroke-linecap="round"/>
                    <circle cx="12" cy="16" r="0.8"
                            fill="currentColor"/>
                </svg>


            </div>


        </div>




        <!-- Composição curva azul/branca/amarela -->
        <div class="dashboard-header__curve" aria-hidden="true">
            <div class="dashboard-header__yellow-curve"></div>
        </div>




        <!-- Pequena composição de pontos -->
        <div class="dashboard-header__dots" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>


    </header>




    <main class="dashboard-main">


    <section class="privacy-notice">


        <div class="privacy-notice__icon">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L19 5V11C19 16 16 20 12 22C8 20 5 16 5 11V5L12 2Z"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linejoin="round"/>


                <rect x="9" y="10" width="6" height="5" rx="1"
                    stroke="currentColor"
                    stroke-width="1.6"/>


                <path d="M10 10V8.5C10 7.4 10.9 6.5 12 6.5C13.1 6.5 14 7.4 14 8.5V10"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"/>
            </svg>
        </div>


        <div class="privacy-notice__content">


            <h2>AVISO DE PRIVACIDADE</h2>


            <p>
                Este sistema processa arquivos temporariamente apenas para análise de faltas escolares.
                Nenhum dado é armazenado permanentemente. Todos os dados são removidos da memória ao final
                da sessão ou ao fechar o sistema.
            </p>


        </div>


    </section>


    <section class="upload-section">


        <div class="section-heading">


            <div class="section-heading__icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 16V4"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"/>


                    <path d="M7.5 8.5L12 4L16.5 8.5"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>


                    <path d="M5 14V19C5 20.1 5.9 21 7 21H17C18.1 21 19 20.1 19 19V14"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"/>
                </svg>
            </div>


            <div>
                <h2>IMPORTAR ARQUIVO</h2>


                <p>
                    Selecione o relatório de frequência que deseja analisar.
                </p>
            </div>


        </div>




        <div class="upload-area" id="uploadArea">


            <input
                type="file"
                id="fileInput"
                name="arquivo"
                accept=".pdf,.xls,.xlsx"
                hidden
            >




            <div class="upload-area__default" id="uploadDefault">


                <div class="upload-area__icon">


                    <svg viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">


                        <path d="M12 15V3"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"/>


                        <path d="M7.5 7.5L12 3L16.5 7.5"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>


                        <path d="M5 12V19C5 20.1 5.9 21 7 21H17C18.1 21 19 20.1 19 19V12"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>


                    </svg>


                </div>




                <h3>
                    Enviar arquivo
                </h3>




                <p>
                    Arraste o arquivo para esta área ou
                    <button type="button" class="upload-area__link" id="chooseFile">
                        clique para selecionar
                    </button>
                    do computador.
                </p>




                <span class="upload-area__formats">
                    Formatos aceitos: PDF, XLS e XLSX
                </span>


            </div>




            <div class="upload-area__selected" id="uploadSelected">


                <div class="selected-file__icon">


                    <svg id="selectedFileIcon"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">


                        <path d="M14 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V8L14 2Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linejoin="round"/>


                        <path d="M14 2V8H20"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linejoin="round"/>


                    </svg>


                </div>




                <div class="selected-file__info">


                    <strong id="selectedFileName">
                        arquivo.pdf
                    </strong>


                    <span id="selectedFileSize">
                        0 KB
                    </span>


                </div>




                <button
                    type="button"
                    class="selected-file__remove"
                    id="removeFile"
                    aria-label="Remover arquivo"
                >


                    <svg viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">


                        <path d="M6 6L18 18"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"/>


                        <path d="M18 6L6 18"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"/>


                    </svg>


                </button>


            </div>


        </div>




        <p class="upload-error" id="uploadError">
            Arquivo não permitido. Selecione um arquivo PDF, XLS ou XLSX.
        </p>


    </section>

        <section class="dashboard-controls">


        <div class="absence-limit">


            <div class="control-heading">


                <div class="control-heading__icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 3V21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>


                        <path d="M5 8L12 3L19 8"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>


                        <path d="M5 16L12 21L19 16"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>
                    </svg>
                </div>


                <div>
                    <h2>LIMITE DE FALTAS</h2>
                    <p>Defina a quantidade mínima de faltas para gerar o alerta.</p>
                </div>


            </div>




            <div class="limit-selector">


                <button
                    type="button"
                    class="limit-selector__button"
                    id="decreaseLimit"
                    aria-label="Diminuir limite"
                >
                    −
                </button>


                <div class="limit-selector__value">
                    <span id="limitValue">100</span>
                    <small>faltas</small>
                </div>


                <button
                    type="button"
                    class="limit-selector__button"
                    id="increaseLimit"
                    aria-label="Aumentar limite"
                >
                    +
                </button>


            </div>


        </div>




        <div class="dashboard-actions">


            <div class="control-heading">


                <div class="control-heading__icon control-heading__icon--yellow">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 5V19"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>


                        <path d="M5 12H19"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>
                    </svg>
                </div>


                <div>
                    <h2>AÇÕES</h2>
                    <p>Gerencie o arquivo selecionado.</p>
                </div>


            </div>




            <div class="actions-buttons">


                <button
                    type="button"
                    class="action-button action-button--secondary"
                    id="viewFile"
                    disabled
                >


                    <svg viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">


                        <path d="M2.5 12C4.5 7.5 8 5 12 5C16 5 19.5 7.5 21.5 12C19.5 16.5 16 19 12 19C8 19 4.5 16.5 2.5 12Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linejoin="round"/>


                        <circle cx="12"
                                cy="12"
                                r="3"
                                stroke="currentColor"
                                stroke-width="1.7"/>


                    </svg>


                    <span>Visualizar arquivo</span>


                </button>




                <button
                    type="button"
                    class="action-button action-button--primary"
                    id="extractData"
                    disabled
                >


                    <svg viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">


                        <path d="M12 3V17"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>


                        <path d="M7 12L12 17L17 12"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"/>


                        <path d="M5 21H19"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"/>


                    </svg>


                    <span>Extrair dados</span>


                </button>


            </div>


        </div>


    </section>

   
    </main>


</body>
</html>
