<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>{{ (isset($page)) ? ucwords(str_replace('-',' ', $page)) : 'Dashboard' }}</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{ (isset($page)) ? ucwords($page) : 'Dashboard' }}</li>
      </ol>
    </section>