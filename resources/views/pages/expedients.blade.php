@extends('layout.base')

@section('content')
    <expedients-app style="display:block; height:100%;"></expedients-app>

    <!-- Button trigger modal -->

  <button style="position: absolute; left: 1%; top: 20%;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Launch demo modal
  </button>
  
  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
@vite('node_modules/bootstrap/dist/js/bootstrap.js')
  {{-- NOW LISTENS TO A VARIABLE IN THE GOOD RUN SHOULD LISTEN TO FLASH SESSION --}}
    @if($reopenModal)
          <script>
             /* document.addEventListener("DOMContentLoaded", function() {
                var myModal = document.getElementById("exampleModal");
                var myModalInstance = new bootstrap.Modal(myModal);
                myModalInstance.show();
            }); */
        </script>
    @endif
  
@endsection