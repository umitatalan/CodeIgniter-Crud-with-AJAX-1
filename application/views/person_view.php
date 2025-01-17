    <h3>Müşteriler</h3>
    <br />
    <button class="btn btn-success" onclick="add_person()"><i class="glyphicon glyphicon-plus"></i> Müşteri Tanımla</button>
    <br />
    <br />
    <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Adı</th>
          <th>Soyadı</th>
          <th>Telefon</th>
          <th>Email</th>
          <th style="width:125px;">İşlemler</th>
        </tr>
      </thead>
      <tbody>
      </tbody>

      <tfoot>
        <tr>
          <th>Adı</th>
          <th>Soyadı</th>
          <th>Telefon</th>
          <th>Email</th>
          <th>İşlemler</th>
        </tr>
      </tfoot>
    </table>

  <script type="text/javascript">

    var save_method; //for save method string
    var table;

    $('ul li a').removeClass('active');
    $('#menuPerson').addClass('active');

    $(document).ready(function() {
      table = $('#table').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/tr.json',
        },

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('person/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
          "targets": [ -1 ], //last column
          "orderable": false, //set not orderable
        },
        ],

      });
    });

    function add_person()
    {
      save_method = 'add';
      $('#form')[0].reset(); 
      $('#modal_form').modal('show'); 
      $('.modal-title').text('Müşteri Tanımla'); 
    }

    function edit_person(id)
    {
      save_method = 'update';
      $('#form')[0].reset();

      $.ajax({
        url : "<?php echo site_url('person/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
           
            $('[name="id"]').val(data.id);
            $('[name="Name"]').val(data.Name);
            $('[name="Surname"]').val(data.Surname);
            $('[name="Phone"]').val(data.Phone);
            $('[name="Email"]').val(data.Email);
            
            $('#modal_form').modal('show'); 
            $('.modal-title').text('Müşteri Bilgi Düzenle'); 
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Veriler alınırkan bir hata oluştu! Hata: ' + textStatus);
        }
    });
    }

    function reload_table()
    {
      table.ajax.reload(null,false);
    }

    function save()
    {
      var url;
      if(save_method == 'add') 
      {
          url = "<?php echo site_url('person/ajax_add')?>";
      }
      else
      {
        url = "<?php echo site_url('person/ajax_update')?>";
      }

          $.ajax({
            url : url,
            type: "POST",
            data: $('#form').serialize(),
            dataType: "JSON",
            success: function(data)
            {
               $('#modal_form').modal('hide');
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Ekleme / Düzenlemede Hata! ' + textStatus);
            }
        });
    }

    function delete_person(id)
    {
      if(confirm('Bu veriyi silmek istediğinize emin misiniz?'))
      {
          $.ajax({
            url : "<?php echo site_url('person/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
               $('#modal_form').modal('hide');
               reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Veri silmede hata! ' + textStatus);
            }
        });
         
      }
    }

  </script>

  <!-- Bootstrap modal -->
  <div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Müşteri</h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form" class="form-horizontal">
          <input type="hidden" value="" name="id"/> 
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Adı</label>
              <div class="col-md-9">
                <input name="Name" placeholder="Adı" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Soyadı</label>
              <div class="col-md-9">
                <input name="Surname" placeholder="Soyadı" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Telefon</label>
              <div class="col-md-9">
                <input name="Phone" placeholder="Telefon" class="form-control" type="text">
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Email</label>
              <div class="col-md-9">
                <input name="Email" placeholder="Email"class="form-control">
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Kaydet</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">İptal</button>
          </div>
        </div>
    </div>
    </div>
 
  </body>
</html>