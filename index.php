<?php
    include('session.php');
    if ($login_role == 0) {
        header("location:admin.php");
        die();
    }
    $ses_sql = mysqli_query($db, "select * from fields");
    $fields = array();
    while ($row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC)) {
        $fields[$row['field_id']] = $row;
    }

    function display($field)
    {
        echo '<div class="field">
                    <span class="field-name">'
                        .$field['field_label'] .
                        '<div class="hover">
                            <i class="fa fa-info-circle tooltip"></i>
                            <div class="tooltipcontainer">' .  $field['description'] . '</div>
                        </div>
                    </span>';

                    $editable = $field['editable']?'readonly':'';

                    if(!$field['type'])
                    {
                        echo '<input class="field-value" type="number" id="' . $field['field_id']  . '" value="' . $field['value'] . '"' . $editable . '>';
                    }
                    else
                    {
                        $valuearray = preg_split('/,/',$field['value']);
                        echo '<select id="' . $field['field_id'] . '" class="field-value">';
                        foreach ($valuearray as $key => $value) {
                            echo '<option value="' . $value . '">' . $value . '</option>';
                        }
                            
                        echo '</select>';
                    }

                    echo '<span class="unit">' . $field['unit'] . '</span>
                </div>';
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
                            <?php display($fields['f1']);?>
                            <?php display($fields['f4']);?>
                            <?php display($fields['f5']);?>
                            <div class="field">
                                <span class="field-name">
                                    Demande Spoc
                                    <div class="hover">
                                        <i class="fa fa-info-circle tooltip"></i>
                                        <div class="tooltipcontainer"><?php echo $fields['f18']['description'];?></div>
                                    </div>
                                </span>
                                <label class="custom-checkbox">
                                    <input id="f18" type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="field">
                                <span class="field-name">
                                    Recouvrement
                                    <div class="hover">
                                        <i class="fa fa-info-circle tooltip"></i>
                                        <div class="tooltipcontainer"><?php echo $fields['f20']['description'];?></div>
                                    </div>
                                </span>
                                <label class="custom-checkbox">
                                    <input id="f20" type="checkbox" checked="checked">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                           <?php display($fields['f32']);?>
                        </div>
                        <div class="content-col-right">
                            <?php display($fields['f19']);?>
                            <?php display($fields['f21']);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="center-col">
                <div class="row">
                    <h2 class="title">Contrat salarié</h2>
                    <?php display($fields['f6']);?>
                    <?php display($fields['f7']);?>
                    <?php display($fields['f2']);?>
                    <?php display($fields['f3']);?>
                </div>
                <div class="row">
                    <h2 class="title">Frais fixe par salarié</h2>
                    <?php display($fields['f8']);?>
                    <?php display($fields['f9']);?>
                    <?php display($fields['f10']);?>
                    <?php display($fields['f11']);?>
                    <div class="field">
                        <span class="field-name">Formation</span>
                        <input class="field-value" id="f12" value="2200" readonly />
                        <span class="unit">€</span>
                    </div>
                    <?php display($fields['f13']);?>
                    <?php display($fields['f14']);?>
                    <?php display($fields['f15']);?>
                    <?php display($fields['f16']);?>
                    <?php display($fields['f17']);?>
                </div>
                <div class="row">
                    <h2 class="title">Frais variable par salarié</h2>
                    <?php display($fields['f23']);?>
                </div>
            </div>
            <div class="right-col">
                <div class="row">
                    <h2 class="title">Salaire salarié</h2>
                    <?php display($fields['f30']);?>
                    <?php display($fields['f31']);?>
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
<script src="./js/calc.js"></script>

</html>