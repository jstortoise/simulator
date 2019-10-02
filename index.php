<?php
    include('session.php');
    if ($login_role == 0) {
        header("location:admin.php");
        die();
    }
    $ses_sql = mysqli_query($db, "select * from fields");
    $fields = array();
    while ($row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC)) {
        if ($row['type'] == 0) {
            $fields[$row['field_id']] = $row['value'];
        }
    }
?>

<body>
    <section>
        <a href="./logout.php">Logout (<?php echo $login_user;?>)</a>
    </section>

    <section id="header">
        <div class="container">
            <h1>This is Sample of Salary simulator. If you use this simulator you can know your state…</h1>
        </div>
    </section>
    
    <section id="content">
        <div class="container">
            <div class="left-col">
                <div class="row">
                    <h2 class="title">Contrat entreprise</h2>
                    <div class="content-col">
                        <div class="content-col-left">
                            <div class="field">
                                <span class="field-name">
                                    Forfait Client jour actuel
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </span>
                                <input class="field-value" type="number" id="f1" value="500">
                                <span class="unit">€</span>
                            </div>
                            <div class="field">
                                <span class="field-name">Reduction Client</span>
                                <input class="field-value" id="f4" value="12" readonly />
                                <span class="unit">%</span>
                            </div>
                            <div class="field">
                                <span class="field-name">Forfait Client jour avec remise de base</span>
                                <input class="field-value" id="f5" value="440" readonly />
                                <span class="unit">€</span>
                            </div>
                            <div class="field">
                                <span class="field-name">Demande Spoc</span>
                                <label class="custom-checkbox">
                                    <input id="f18" type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="field">
                                <span class="field-name">Recouvrement</span>
                                <label class="custom-checkbox">
                                    <input id="f20" type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="field">
                                <span class="field-name">Forfait client jour final</span>
                                <input class="field-value" id="f32" value="1140" readonly />
                                <span class="unit"></span>
                            </div>
                        </div>
                        <div class="content-col-right">
                            <div class="field">
                                <span class="field-name">Remise Spoc</span>
                                <input class="field-value" id="f19" value="500" readonly />
                                <span class="unit">€</span>
                            </div>
                            <div class="field">
                                <span class="field-name">Remise recouvrement</span>
                                <input class="field-value" id="f21" value="200" readonly />
                                <span class="unit">€</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="center-col">
                <div class="row">
                    <h2 class="title">Contrat salarié</h2>
                    <div class="field">
                        <span class="field-name">Salaire mensuel actuel net</span>
                        <input type="number" class="field-value" id="f6" value="3100">
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Type de contrat</span>
                        <select id="f7" class="field-value">
                            <option value="cdi">CDI</option>
                            <option value="non">Non</option>
                        </select>
                        <span class="unit"></span>
                    </div>
                    <div class="field">
                        <span class="field-name">Nombre de jour travaillé</span>
                        <input class="field-value" id="f2" value="216" readonly />
                        <span class="unit">jour</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Nombre de RTT</span>
                        <input class="field-value" type="number" id="f3" value="11" max="11" min="0">
                        <span class="unit">jour</span>
                    </div>
                </div>
                <div class="row">
                    <h2 class="title">Frais fixe par salarié</h2>
                    <div class="field">
                        <span class="field-name">Feuille de paye</span>
                        <input class="field-value" id="f8" value="<?php echo $fields['f8'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Mutuelle</span>
                        <select id="f9" class="field-value">
                            <option value="20">20</option>
                            <option value="30">30</option>
                        </select>
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Assurance salarié</span>
                        <input class="field-value" id="f10" value="<?php echo $fields['f10'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Assurance ESN</span>
                        <input class="field-value" id="f11" value="<?php echo $fields['f11'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Formation</span>
                        <input class="field-value" id="f12" value="2200" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">URSAAF</span>
                        <input class="field-value" id="f13" value="<?php echo $fields['f13'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">AG2R</span>
                        <input class="field-value" id="f14" value="<?php echo $fields['f14'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Outils</span>
                        <input class="field-value" id="f15" value="<?php echo $fields['f15'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Admin CDI</span>
                        <input class="field-value" id="f16" value="<?php echo $fields['f16'];?>" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Fees TT</span>
                        <input class="field-value" id="f17" value="1312.5547445255472" readonly />
                        <span class="unit">€</span>
                    </div>
                </div>
                <div class="row">
                    <h2 class="title">Frais variable par salarié</h2>
                    <div class="field">
                        <span class="field-name">Réserve salaire en mois</span>
                        <input class="field-value" type="number" id="f23" value="3">
                        <span class="unit">mois</span>
                    </div>
                </div>
            </div>
            <div class="right-col">
                <div class="row">
                    <h2 class="title">Salaire salarié</h2>
                    <div class="field">
                        <span class="field-name">Salaire mensuel cible initial</span>
                        <input class="field-value" id="f30" value="10937.956204379561" readonly />
                        <span class="unit">€</span>
                    </div>
                    <div class="field">
                        <span class="field-name">Salaire mensuel cible après 3 mois</span>
                        <input class="field-value" id="f31" value="13672.445255474453" readonly />
                        <span class="unit">€</span>
                    </div>
                </div>
                <div class="result">
                    <span class="field-name">Augmentation du salaire</span>
                    <div id="f34">
                        <!-- <p id="f34-value">341.04662114433717%</p> -->
                        <p id="f34-text">Contacter nous, si vous êtes intérés pour avoir 341.04662114433717%
                            d'augmentation</p>
                    </div>
                </div>
                
                <input type="hidden" id="f24" value="<?php echo $fields['f24'];?>" readonly />
                <input type="hidden" id="f27" value="<?php echo $fields['f27'];?>" readonly />
            </div>
        </div>
    </section>

</body>
<script src="./js/jquery.min.js"></script>
<script src="./js/script.js"></script>

</html>