
<div class="col-md-12">
  <div class="form-group"> @if (Session::has('success'))
  
    <div class="alert alert-success">{{Session()->get('success')}}{{ Session::get('success') }}</div>
    @endif
    
    @if(Session::has('error'))
    <div class="alert alert-danger"> {{ Session::get('error') }} </div>
    @endif 
    
    @if ($errors->has())
    <div class="alert alert-danger"> @foreach ($errors->all() as $error)
      {{ $error }}<br>
      @endforeach </div>
    @endif </div>
</div>
