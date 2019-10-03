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

    function computeValue() {
        var f1 = $("#f1").val();
        var f4 = $("#f4").val() / 100;
        var f2 = $("#f2").val();
        var f3 = $("#f3").val();
        var f6 = $("#f6").val();
        var f19 = -f18 * 500;
        var f21 = -f20 * 200;
        var f8 = $("#f8").val();
        var f9 = $("#f9").val();
        var f10 = $("#f10").val();
        var f11 = $("#f11").val();
        var f13 = $("#f13").val();
        var f14 = $("#f14").val();
        var f15 = $("#f15").val();
        var f16 = $("#f16").val();
        var f24 = 0.25;
        $("#f5").val(f1 * (1 - f4));
        var f5 = $("#f5").val();
        $("#f12").val(f5 * 5);
        var f12 = $("#f12").val();
        var f32 = f5 - f19 - f21;
        $("#f32").html(f32);
    
        var f33 = (f32 * (f2 - f3)) / 12;
        var f26 = 1;
        var f27 = f26 * 400;
    
        var f30 =
            (f33 - f8 - f9 - f10 - f11 - f12 - f13 - f14 - f15 - f16 - f27) /
            (1 + 0.12 + f24);
        $("#f30").html(f30);
    
        var f17 = 0.12 * f30;
        $("#f17").html(f17);
    
        var f31 = f33 - f8 - f9 - f10 - f11 - f12 - f13 - f14 - f15 - f16 - f17 - f27;
        $("#f31").html(f31);
    
        var f25 = f30 * f24; // tinh gia tri f25 phai co f30
    
        var f34 = (100 * (f31 - f6)) / f6;
        $("#f34-value").html(`${f34}%`);
        if (f34 > 0) {
            $("#f34-text").html(
                `Contacter nous, si vous êtes intérés pour avoir ${f34}% d'augmentation`
            );
        } else {
            $("#f34-text").html(
                `Votre salaire actuelle semble optimisé par rapport aux services attendues de votre employeyur`
            );
        }
    }
});
