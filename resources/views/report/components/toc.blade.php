{{-- <div class="page-break"></div> --}}
<div class="toc-page">
  <h3 class="text-center">TABLE OF CONTENTS</h3>
  
  <table class="table table-borderless">
    <tbody>
      <tr>
        <td><span class="number">1.</span> COVER</td>
      </tr>
      <tr>
        <td><span class="number">2.</span> APPROVAL OF THICKNESS MEASUREMENT REPORT</td>
      </tr>
      <tr>
        <td><span class="number">3.</span> TABLE OF CONTENTS</td>
      </tr>
      <tr>
        <td><span class="number">4.</span> COMPANY CERTIFICATE</td>
      </tr>
      <tr>
        <td><span class="number">5.</span> PERSONNEL CERTIFICATE</td>
      </tr>
      <tr>
        <td><span class="number">6.</span> EQUIPMENT CERTIFICATE</td>
      </tr>

      @php $counter = 7; @endphp
      @foreach($categories as $category)
        @php $hasForm = false; @endphp
        @foreach($form_data_all[$category->id] as $form_type)
          @if($form_type->form->count() > 0)
            @php $hasForm = true; @endphp
          @endif
        @endforeach

        @if($hasForm)
          <tr>
            <td><span class="number">{{ $counter++ }}.</span> {{ strtoupper($category->title) }}</td>
          </tr>
          @php $romanCounter = 1; @endphp
        @endif
      @endforeach
      
    </tbody>
  </table>
</div>

<style>
.toc-page {
  padding: 20px;
  font-size: 18px;
  text-transform: uppercase;
}
.toc-page h3 {
  font-size: 20px;
}
.page-break {
  page-break-after: always;
}
.text-center {
  text-align: center;
}
.table {
  width: 100%;
  margin-bottom: 1rem;
}
.table td {
  padding: 8px;
  vertical-align: top;
}
.number {
  display: inline-block;
  width: 25px;
}
</style>
