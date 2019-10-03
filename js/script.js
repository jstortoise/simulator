$(document).ready(function() {

  function init()
  {
      $('#medit').find('input').each(function(){
        $(this).val('');
      })

      $('.fieldtype').val('0');
      $('.add_value_box').css('display','none');
      $('.value_container').css('display','none');
  }

  function initvaluecontainer(value)
  {
    if(value == '0')
    {
      $('.add_value_box').css('display','none');
      $('.value_container').css('display','none');
    }
    else
    {
      $('.add_value_box').css('display','');
      $('.value_container').css('display','');
    }
  }

  $('.fieldtype').change(function(){
      var value = $(this).val();
      
      initvaluecontainer(value);
      $('.value_container').html('');
  })

  $('.add_value').click(function(){
      var value = $('.value_edit').val();

      if(!value)
      {
        return;
      }
      var value_array = [];
      $('.value_container').find('.item').each(function(){
        value_array.push($(this).attr('itemvalue'));
      })

      if(value_array.indexOf(value) == -1)
      {
        $('.value_container').append('<span class="item" itemvalue="' + value + '" style="margin-right:10px;">' + value + '<i class="fa fa-times-circle delete_value" style="margin-left:10px;"></i></span>');
      }

      $('.value_edit').val('');
  })

  $('.change').click(function(){
    var fieldid = $(this).attr('attr_id');
    var action = 'Update';
    if(!fieldid)
    {
      fieldid = $('.field_id').val();
      action = 'Add';
    }

    name = $('.name_edit').val();
    type = $('.fieldtype').val();
    unit = $('.unit_edit').val();
    description = $('.description_edit').val();
    value = "";
    if(type == '0')
    {
      value = $('.value_edit').val();
    }
    else
    {
      value = [];
      $('.value_container').find('.item').each(function(){
        value.push($(this).attr('itemvalue'));
      })

      value = value.join(',');
    }

    $.ajax({
      url:'editsave.php',
      type:'POST',
      data:{action:action,data:{field_label:name,field_id:fieldid,unit:unit,type:type,description:description,value:value}},
      success:function(res)
      {
        res = JSON.parse(res);
        if(res.success)
        {
          window.location.reload();
        }
      }
    })

  })

  $(document).on('click','.delete_value',function(){
    $(this).parent().remove();
  })

  $('.add_new').click(function(){
    $.ajax({
      url:'getdata.php',
      type:'POST',
      data:{action:'autofieldid'},
      success:function(res){
        res = JSON.parse(res);
        init();
        $('.field_id').val(res.fieldid);
        $('.change').attr('attr_id','');
        $('#medit').modal('show');
      }
    })
  })

  $(".save_add").click(function(){
      $.ajax({
          url: 'data.php',
          type:'post',
          data:{name_add:$('.name_add').val(), value_add:$('.value_add').val(), unit_add:$('.unit_add').val(), type_add:$('.type_add').val(), description_add:$('.description_add').val()},
          success:function(res)
          {
            window.location.reload();
          }
      })
  })

  // $(".chang").click(function(){
  //     $.ajax({
  //         url: 'editsave.php',
  //         type:'post',
  //         data:{id: $('.id_edit').val(), name_edit:$('.name_edit').val(), value_edit:$('.value_edit').val(), unit_edit:$('.unit_edit').val(), type_edit:$('.type_edit').val(), description_edit:$('.description_edit').val()},
  //         success:function(res)
  //         {
  //           window.location.reload();
  //         }
  //     })
  // })

  $(document).on("click", ".medit", function(){
    $.ajax({
      url:'getdata.php',
      type:'post',
      data:{edit_id:$(this).attr('attr_id')},
      success:function(res)
      {
        var data = JSON.parse(res);

        init();
        $('.value_container').html('');
        initvaluecontainer(data.type);

        $('.field_id').val(data.field_id);
        $('.name_edit').val(data.field_label);
        $('.unit_edit').val(data.unit);
        $('.fieldtype').val(data.type);
        $('.description_edit').val(data.description);

        if(data.type == '0')
        {
          $('.value_edit').val(data.value);
        }
        else
        {
          var valuearray = data.value.split(',');

          for(item in valuearray)
          {
            $('.value_container').append('<span class="item" itemvalue="' + valuearray[item] + '" style="margin-right:10px;">' + valuearray[item] + '<i class="fa fa-times-circle delete_value" style="margin-left:10px;"></i></span>')
          }
        }

        $('.change').attr('attr_id',data.field_id);
        $('#medit').modal('show');    
      }
    })
    
  })

  $(document).on('click','.editformula',function(){
    var fieldid = $(this).attr('attr_id');
    $('.save_formula').attr('fieldid',fieldid);

    $.ajax({
      url:'getdata.php',
      type:'POST',
      data:{action:'getformular',fieldid:fieldid},
      success:function(res){
        if(res)
        {
          res = JSON.parse(res);
          if(res && res.formula)
          {
            $('.formula_desc').val(res.formula);
          }  
          else
          {
            $('.formula_desc').val("");
          }
        }
        else
        {
          $('.formula_desc').val("");
        }

         $('#formula').modal('show');
        
      }
    })
  })

  $('.save_formula').click(function(){
    var fieldid = $(this).attr('fieldid');
    var formula = $('.formula_desc').val();

    $.ajax({
      url:'editsave.php',
      type:'POST',
      data:{action:'updateformula',field_id:fieldid,formula:formula},
      success:function(res)
      {
        res = JSON.parse(res);
        if(res.success)
        {
          $('#formula').modal('hide');
        }
      }
    })
  })

  $('.delete').click(function(){
    var id = $(this).attr('attr_id');
    $('.delete_save').attr('attr_id',id);
  })

  $('.delete_save').click(function(){
      $.ajax({
        url:'delete.php',
        type:'post',
        data:{delete_id:$(this).attr('attr_id')},
        success:function(res)
        {
          window.location.reload();
        }
    })
  })
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

$(document).ready(function(){
  $('.tooltip').hover(function(){
    $(this).parent().find('.tooltipcontainer').eq(0).show();
  })

  $('.tooltip').mouseout(function(){
    $(this).parent().find('.tooltipcontainer').eq(0).hide();
  })
})