function Game() {
  this.data = null;

  this.move = function(d) {
    $('#loading').show(10);
    $.post("/rpc/move/"+Math.random(), { 'dir': d },
      function(data){
        this.data = data;
        if (this.data.r != undefined){
          document.location = this.data.r;
        }
        $('#xc').html(this.data.coord.x);
        $('#yc').html(this.data.coord.y);
        var map = this.data.map;
        //console.log(data.map);
        var my = 1;
        for (var y in map){
          var row = map[y]
          var mx = 1
          for (var x in row){
            var t = row[x]["t"];
            var o = row[x]["o"];
            var tile = '#tl_'+mx+'_'+my;
            var over = '#i_'+mx+'_'+my;
            $(tile).attr('class', 't_'+t);
            $(over).attr('class', 't_'+o);
            //console.log(tile);
            mx++;
          }
          my++;
        }
        $('#ll1').attr('class', 'l'+this.data.l);
        $('#cl1').attr('class', 'c'+this.data.c);
        $('#rl1').attr('class', 'r'+this.data.r);
        $('#mmap').html(this.data.map);
        //console.log(this.data);
        $('#loading').hide(40);
    }, "json");
  }
}

$(document).ready(function(){
  g = new Game();
  $('.dir').click(function(event){
    var mt = $(this).attr('id');
    g.move(mt);
    return false;
  })
});