$(document).ready(function() {
  var f18;
  var f20;

  if ($("#f18").prop("checked")) {
    f18 = 1;
  } else {
    f18 = 0;
  }

  if ($("#f20").prop("checked")) {
    f20 = 1;
  } else {
    f20 = 0;
  }

  $("#f18").change(function() {
    if ($("#f18").prop("checked")) {
      f18 = 1;
    } else {
      f18 = 0;
    }
  });

  $("#f20").change(function() {
    if ($("#f20").prop("checked")) {
      f20 = 1;
    } else {
      f20 = 0;
    }
  });

  $("input").keydown(function() {
    computeValue(f18, f20);
  });
  $("input").change(function() {
    computeValue(f18, f20);
  });
  $("select").change(function() {
    computeValue(f18, f20);
  });
});

function computeValue(f18, f20) {
  console.log(`\n\n\n\n\nf18 : ${f18} \nf20 : ${f20}`);
  let f1 = $("#f1").val();
  let f4 = $("#f4").val() / 100;
  let f2 = $("#f2").val();
  let f3 = $("#f3").val();
  let f6 = $("#f6").val();
  let f19 = -f18 * 500;
  let f21 = -f20 * 200;
  let f8 = $("#f8").val();
  let f9 = $("#f9").val();
  let f10 = $("#f10").val();
  let f11 = $("#f11").val();
  let f13 = $("#f13").val();
  let f14 = $("#f14").val();
  let f15 = $("#f15").val();
  let f16 = $("#f16").val();
  let f24 = 0.25;
  $("#f5").val(f1 * (1 - f4));
  let f5 = $("#f5").val();
  console.log(`f5 : ${f5}`);
  $("#f12").val(f5 * 5);
  let f12 = $("#f12").val();

  console.log(`f8 : ${f8}`);
  console.log(`f9 : ${f9}`);
  console.log(`f10 : ${f10}`);
  console.log(`f11 : ${f11}`);
  console.log(`f12 : ${f12}`);
  console.log(`f13 : ${f13}`);
  console.log(`f14 : ${f14}`);
  console.log(`f15 : ${f15}`);
  console.log(`f16 : ${f16}`);

  let f32 = f5 - f19 - f21;
  $("#f32").html(f32);
  console.log(`f32 : ${f32}`);

  let f33 = (f32 * (f2 - f3)) / 12;
  console.log(`f33 : ${f33}`);
  let f26 = 1;
  let f27 = f26 * 400;
  console.log(`f27 : ${f27}`);

  let f30 =
    (f33 - f8 - f9 - f10 - f11 - f12 - f13 - f14 - f15 - f16 - f27) /
    (1 + 0.12 + f24);
  $("#f30").html(f30);
  console.log(`f30 : ${f30}`);

  let f17 = 0.12 * f30;
  $("#f17").html(f17);
  console.log(`f17 : ${f17}`);

  let f31 = f33 - f8 - f9 - f10 - f11 - f12 - f13 - f14 - f15 - f16 - f17 - f27;
  console.log(`f31 : ${f31}`);
  $("#f31").html(f31);

  let f25 = f30 * f24; // tinh gia tri f25 phai co f30

  // let f30 = f33 - f8 - f9 - f10 - f11 - f12 - f13 - f14 - f15 - f16 - f17 - f25 - f27; // ko tinh duoc gia tri f25

  let f34 = (100 * (f31 - f6)) / f6;
  console.log(`f34 : ${f34}`);
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
