<!-- Content Header (Page header) -->
    <section class="content-header">
   	  @if(isset($title))
      <h1>{{ ucwords(str_replace('-',' ', $title)) }}</h1>
      @else
      <h1>{{ (isset($page)) ? ucwords(str_replace('-',' ', $page)) : 'Dashboard' }}</h1>
      @endif
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ (isset($page)) ? ucwords($page) : 'Dashboard' }}</li>
      </ol>
    </section>