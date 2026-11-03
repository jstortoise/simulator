<?php
    include('session.php');

    if ((int)$login_role !== 1) {
        header('Location: admin.php');
        exit;
    }

    $field_sql = mysqli_query($db, "SELECT * FROM fields");
    $fields = array();
    if ($field_sql) {
        while ($row = mysqli_fetch_assoc($field_sql)) {
            if (is_safe_field_id($row['field_id'])) {
                $fields[$row['field_id']] = $row;
            }
        }
    }

    $formula_sql = mysqli_query($db, "SELECT * FROM formulas");

    function display($field) {
        if (!is_array($field)) {
            return;
        }
        $fieldId = is_safe_field_id(isset($field['field_id']) ? $field['field_id'] : '') ? $field['field_id'] : '';
        $label = h(isset($field['field_label']) ? $field['field_label'] : '');
        $description = h(isset($field['description']) ? $field['description'] : '');
        $value = h(isset($field['value']) ? $field['value'] : '');
        $unit = h(isset($field['unit']) ? $field['unit'] : '');
        $readonly = !empty($field['editable']) ? '' : ' readonly';

        echo '<div class="field">';
        echo '<span class="field-name">' . $label;
        echo '<div class="hover"><i class="fa fa-info-circle tooltip"></i>';
        echo '<div class="tooltipcontainer">' . $description . '</div></div></span>';

        if ($fieldId === '') {
            echo '<input class="field-value" type="text" value="" disabled>';
        } else if (empty($field['type'])) {
            echo '<input class="field-value" type="number" id="' . h($fieldId) . '" value="' . $value . '"' . $readonly . '>';
        } else {
            echo '<select id="' . h($fieldId) . '" class="field-value">';
            $parts = explode(',', isset($field['value']) ? (string)$field['value'] : '');
            foreach ($parts as $option) {
                $option = h($option);
                echo '<option value="' . $option . '">' . $option . '</option>';
            }
            echo '</select>';
        }

        echo '<span class="unit">' . $unit . '</span></div>';
    }

    function showNewFields($fields) {
        foreach ($fields as $key => $values) {
            if ($values['new_field'] == 1) {
                display($values);
            }
        }
    }
?>

<?php
    $safeFormulas = array();
    if ($formula_sql) {
        while ($row = mysqli_fetch_assoc($formula_sql)) {
            $formulaFieldId = isset($row['field_id']) ? $row['field_id'] : '';
            $formula = isset($row['formula']) ? $row['formula'] : '';
            if (is_safe_field_id($formulaFieldId) && is_safe_formula($formula)) {
                $safeFormulas[] = array(
                    'field_id' => $formulaFieldId,
                    'formula' => $formula,
                );
            }
        }
    }
    render_page_head();
?>
<script>
    var formulas = <?php echo json_encode($safeFormulas, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    if (!Array.isArray(formulas)) {
        formulas = [];
    }
</script>
<body>
    <section>
        <a href="./logout.php">Logout (<?php echo h($login_user); ?>)</a>
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
                                        <div class="tooltipcontainer"><?php echo h(isset($fields['f18']['description']) ? $fields['f18']['description'] : ''); ?></div>
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
                                        <div class="tooltipcontainer"><?php echo h(isset($fields['f20']['description']) ? $fields['f20']['description'] : ''); ?></div>
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
                
                <div class="row">
                    <h2 class="title">Additional Fields</h2>
                    <?php showNewFields($fields);?>
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
                    <?php display($fields['f12']);?>
                    <?php display($fields['f13']);?>
                    <?php display($fields['f14']);?>
                    <?php display($fields['f15']);?>
                    <?php display($fields['f16']);?>
                    <?php display($fields['f17']);?>
                </div>
                <div class="row">
                    <h2 class="title">Frais variable par salarié</h2>
                    <?php display($fields['f23']);?>
                    <?php display($fields['f24']);?>
                    <?php display($fields['f25']);?>
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
                        <p id="f34-text">Contacter nous, si vous êtes intérés pour avoir <span id="f34-value">341.04662114433717</span>%
                            d'augmentation</p>
                    </div>
                </div>

                <input type="hidden" id="f24" value="<?php echo h(isset($fields['f24']['value']) ? $fields['f24']['value'] : ''); ?>" readonly />
                <input type="hidden" id="f27" value="<?php echo h(isset($fields['f27']['value']) ? $fields['f27']['value'] : ''); ?>" readonly />
            </div>
        </div>
    </section>

</body>
<script src="./js/jquery.min.js"></script>
<script>
    $(function() {
        var f18 = $("#f18").prop("checked") ? 1 : 0;
        var f20 = $("#f20").prop("checked") ? 1 : 0;

        $("#f18").change(function () {
            f18 = $("#f18").prop("checked") ? 1 : 0;
        });

        $("#f20").change(function () {
            f20 = $("#f20").prop("checked") ? 1 : 0;
        });

        $("input").keydown(function () {
            computeValue(f18, f20);
        });
        
        $("input").change(function () {
            computeValue(f18, f20);
        });
        
        $("select").change(function () {
            computeValue(f18, f20);
        });
        
        $('.tooltip').hover(function () {
            $(this).parent().find('.tooltipcontainer').eq(0).show();
        });

        $('.tooltip').mouseout(function () {
            $(this).parent().find('.tooltipcontainer').eq(0).hide();
        });

        computeValue();

        function readNum(id) {
            var el = document.getElementById(id);
            if (!el) {
                return NaN;
            }
            return el.value * 1;
        }

        function evaluateFormula(expr, vars) {
            if (typeof expr !== 'string' || expr.length > 1000 || !/^[0-9+\-*/(). f]+$/.test(expr)) {
                throw new Error('invalid formula');
            }
            var i = 0;

            function skipSpaces() {
                while (i < expr.length && expr.charAt(i) === ' ') {
                    i++;
                }
            }

            function parseExpr() {
                var val = parseTerm();
                while (true) {
                    skipSpaces();
                    var op = expr.charAt(i);
                    if (op !== '+' && op !== '-') {
                        break;
                    }
                    i++;
                    var rhs = parseTerm();
                    val = op === '+' ? val + rhs : val - rhs;
                }
                return val;
            }

            function parseTerm() {
                var val = parseFactor();
                while (true) {
                    skipSpaces();
                    var op = expr.charAt(i);
                    if (op !== '*' && op !== '/') {
                        break;
                    }
                    i++;
                    var rhs = parseFactor();
                    val = op === '*' ? val * rhs : val / rhs;
                }
                return val;
            }

            function parseFactor() {
                skipSpaces();
                var ch = expr.charAt(i);
                if (ch === '+' || ch === '-') {
                    i++;
                    var signed = parseFactor();
                    return ch === '-' ? -signed : signed;
                }
                if (ch === '(') {
                    i++;
                    var inner = parseExpr();
                    skipSpaces();
                    if (expr.charAt(i) !== ')') {
                        throw new Error('paren');
                    }
                    i++;
                    return inner;
                }
                if (ch === 'f') {
                    i++;
                    var start = i;
                    while (i < expr.length && expr.charAt(i) >= '0' && expr.charAt(i) <= '9') {
                        i++;
                    }
                    if (start === i) {
                        throw new Error('field');
                    }
                    var name = 'f' + expr.slice(start, i);
                    if (!Object.prototype.hasOwnProperty.call(vars, name) || vars[name] === undefined || vars[name] === null || isNaN(vars[name])) {
                        throw new Error('unset');
                    }
                    return Number(vars[name]);
                }
                if ((ch >= '0' && ch <= '9') || ch === '.') {
                    var startNum = i;
                    var dots = 0;
                    while (i < expr.length) {
                        var c = expr.charAt(i);
                        if (c >= '0' && c <= '9') {
                            i++;
                        } else if (c === '.') {
                            dots++;
                            if (dots > 1) {
                                break;
                            }
                            i++;
                        } else {
                            break;
                        }
                    }
                    var num = Number(expr.slice(startNum, i));
                    if (startNum === i || isNaN(num)) {
                        throw new Error('num');
                    }
                    return num;
                }
                throw new Error('token');
            }

            var result = parseExpr();
            skipSpaces();
            if (i !== expr.length || typeof result !== 'number') {
                throw new Error('trailing');
            }
            return result;
        }

        function computeValue() {
            var vars = {
                f1: readNum('f1'),
                f2: readNum('f2'),
                f3: readNum('f3'),
                f4: readNum('f4') / 100,
                f6: readNum('f6'),
                f8: readNum('f8'),
                f9: readNum('f9'),
                f10: readNum('f10'),
                f11: readNum('f11'),
                f13: readNum('f13'),
                f14: readNum('f14'),
                f15: readNum('f15'),
                f16: readNum('f16'),
                f24: readNum('f24'),
                f18: f18 * 1,
                f20: f20 * 1,
                f26: 1,
                f19: -f18 * 500,
                f21: -f20 * 200
            };

            var index = 0;
            while (index <= formulas.length) {
                index++;
                formulas.forEach(function (obj) {
                    if (!obj || !/^f\d{1,6}$/.test(obj.field_id)) {
                        return;
                    }
                    var current = vars[obj.field_id];
                    if (!(current === undefined || isNaN(current))) {
                        return;
                    }
                    try {
                        var result = evaluateFormula(obj.formula, vars);
                        vars[obj.field_id] = result;
                        var input = document.getElementById(obj.field_id);
                        if (input) {
                            input.value = result;
                        }
                    } catch (e) {
                    }
                });
            }

            var f34 = vars.f34;
            if (typeof f34 === 'number' && !isNaN(f34)) {
                if (f34 > 0) {
                    $('#f34-text').text("Contacter nous, si vous êtes intérés pour avoir " + f34 + "% d'augmentation");
                } else {
                    $('#f34-text').text('Votre salaire actuelle semble optimisé par rapport aux services attendues de votre employeyur');
                }
            }
        }

    });
</script>

</html>