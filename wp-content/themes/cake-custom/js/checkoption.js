jQuery(function($){
// チェックボックスをチェックしたら発動
  $('input[name="cake_decorate"]').change(function() {

    // prop()でチェックの状態を取得
    var deco01 = $('#cake_decorate_01').is(":checked");
    // val()でチェックの状態を取得
    var deco02 = $('#cake_decorate_02').is(":checked");
    // is()でチェックの状態を取得
    var deco03 = $('#cake_decorate_03').is(":checked");

    // もしpropがチェック状態だったら
    if (deco01) {
      // propでチェックと出力
      $('#optionbox01').show();
	  $('#optionbox01').addClass("show");
    } else {
      // テキストをリセット
      $('#optionbox01').hide();
	  $('#optionbox01').removeClass("show");
    }

    // もしvalがチェック状態だったら
    if (deco02) {
      // propでチェックと出力
      $('#optionbox02').show();
	  $('#optionbox02').addClass("show");
    } else {
      // テキストをリセット
      $('#optionbox02').hide();
	  $('#optionbox02').removeClass("show");
    }

    if (deco03) {
      // propでチェックと出力
      $('#optionbox03').show();
	  $('#optionbox03').addClass("show");
    } else {
      // テキストをリセット
      $('#optionbox03').hide();
	  $('#optionbox03').removeClass("show");
    }
    

});
});