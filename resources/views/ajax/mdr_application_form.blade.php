<form id="problemEntry" style="position: relative;overflow: hidden;" action="{{ route('requisitions.directsave') }}" method="post">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12 form-group">
            {{Form::label('Applicant Name : ',null,array('class' => 'control-label'))}}
            {{Form::text('applicant_name',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('applicant_name', '<p class="text-danger">:message</p>' ) !!}
        </div>

        <div class="col-md-12 form-group">
            {{Form::label('Applicant Fathers Name: ',null,array('class' => 'control-label'))}}
            {{Form::text('applicant_fathers_name',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('applicant_fathers_name', '<p class="text-danger">:message</p>' ) !!}
        </div>
         <div class="col-md-12 form-group">
            {{Form::label('Applicant Address: ',null,array('class' => 'control-label'))}}
            {{Form::text('applicant_address',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('applicant_address', '<p class="text-danger">:message</p>' ) !!}
        </div>
         <div class="col-md-12 form-group">
            {{Form::label('Applicant Mobile No: ',null,array('class' => 'control-label'))}}
            {{Form::text('mobile',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('mobile', '<p class="text-danger">:message</p>' ) !!}
        </div>
        <div class="col-md-12 form-group">
            {{Form::label('Applicant Email Address: ',null,array('class' => 'control-label'))}}
            {{Form::text('email',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('email', '<p class="text-danger">:message</p>' ) !!}
        </div>
        <div class="col-md-12 form-group">
            {{Form::label('Applicant Highest Education: ',null,array('class' => 'control-label'))}}
            {{Form::text('education',null,array('required','class' => 'form-control'))}}
            {!! $errors->first('education', '<p class="text-danger">:message</p>' ) !!}
        </div>
        <div class="col-md-12 form-group">
                        <label for="inputName" class="col-sm-2 require">Applicant Picture:</label>
                        <div class="col-md-5">
                            <div class="input-group">
                              <input type="file" name="applicant_image" > 
                              {!! $errors->first('applicant_image', '<p class="text-danger">:message</p>' ) !!}
                            </div>
                        </div>
                    </div>
        <div class="col-md-12 form-group">
            {{Form::label('Applicant CV: ',null,array('class' => 'control-label'))}}
          <input type="file" name="applicant_cv" > 
           {!! $errors->first('applicant_cv', '<p class="text-danger">:message</p>' ) !!}
        </div>
        <div class="col-md-12 form-group">
              {{Form::label('remarks',null,array('class' => 'control-label'))}}
              {{Form::textarea('remarks',null,array('required','class' => 'form-control max-length','maxlength'=>250,'rows'=>4))}}
        </div>
        <div class="col-md-4 form-group">
            <button type="submit" class="btn btn-success" id="confirm-btn">Submit Entry</button>
        </div>
    </div>
</form>

