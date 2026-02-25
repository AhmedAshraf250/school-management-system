{{-- Personal information fields --}}
<h6 style="font-family: 'Cairo', sans-serif; color: blue">
    {{ trans('Students_trans.personal_information') }}
</h6>
<br>

{{-- Student names (Arabic/English) --}}
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="name_ar">{{ trans('Students_trans.name_ar') }} <span class="text-danger">*</span></label>
        <input id="name_ar" type="text" name="name_ar" class="form-control"
            value="{{ old('name_ar', $student?->getTranslation('name', 'ar')) }}">
    </div>
    <div class="form-group col-md-6">
        <label for="name_en">{{ trans('Students_trans.name_en') }} <span class="text-danger">*</span></label>
        <input id="name_en" type="text" name="name_en" class="form-control"
            value="{{ old('name_en', $student?->getTranslation('name', 'en')) }}">
    </div>
</div>

{{-- Account credentials (email/password) --}}
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="email">{{ trans('Students_trans.email') }} <span class="text-danger">*</span></label>
        <input id="email" type="email" name="email" class="form-control"
            value="{{ old('email', $student?->email) }}">
    </div>
    <div class="form-group col-md-6">
        <label for="password">{{ trans('Students_trans.password') }}
            @if ($student === null)
                <span class="text-danger">*</span>
            @endif
        </label>
        <input id="password" type="password" name="password" class="form-control">
    </div>
</div>

{{-- Core identity selectors (gender/nationality/blood/date of birth) --}}
<div class="form-row">
    <div class="form-group col-md-3">
        <label for="gender_id">{{ trans('Students_trans.gender') }} <span class="text-danger">*</span></label>
        <select id="gender_id" class="custom-select" name="gender_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($genders as $gender)
                <option value="{{ $gender->id }}" @selected((int) old('gender_id', $student?->gender_id) === $gender->id)>
                    {{ $gender->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="nationality_id">{{ trans('Students_trans.Nationality') }} <span class="text-danger">*</span></label>
        <select id="nationality_id" class="custom-select" name="nationality_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($nationalities as $nationality)
                <option value="{{ $nationality->id }}" @selected((int) old('nationality_id', $student?->nationality_id) === $nationality->id)>
                    {{ $nationality->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="blood_id">{{ trans('Students_trans.blood_type') }} <span class="text-danger">*</span></label>
        <select id="blood_id" class="custom-select" name="blood_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($bloodTypes as $bloodType)
                <option value="{{ $bloodType->id }}" @selected((int) old('blood_id', $student?->blood_id) === $bloodType->id)>
                    {{ $bloodType->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="date_birth">{{ trans('Students_trans.Date_of_Birth') }} <span class="text-danger">*</span></label>
        <input id="date_birth" class="form-control" type="date" name="date_birth"
            value="{{ old('date_birth', optional($student?->date_birth)->format('Y-m-d')) }}">
    </div>
</div>

{{-- Academic placement fields --}}
<h6 style="font-family: 'Cairo', sans-serif; color: blue">
    {{ trans('Students_trans.Student_information') }}
</h6>
<br>

{{-- Academic mapping (grade/classroom/section/guardian/year) --}}
<div class="form-row">
    <div class="form-group col-md-2">
        <label for="grade_id">{{ trans('Students_trans.Grade') }} <span class="text-danger">*</span></label>
        <select id="grade_id" class="custom-select" name="grade_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($grades as $grade)
                <option value="{{ $grade->id }}" @selected((int) old('grade_id', $student?->grade_id) === $grade->id)>
                    {{ $grade->Name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="classroom_id">{{ trans('Students_trans.classrooms') }} <span class="text-danger">*</span></label>
        <select id="classroom_id" class="custom-select" name="classroom_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($classrooms as $classroomId => $classroomName)
                <option value="{{ $classroomId }}" @selected((int) old('classroom_id', $student?->classroom_id) === (int) $classroomId)>
                    {{ $classroomName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="section_id">{{ trans('Students_trans.section') }} <span class="text-danger">*</span></label>
        <select id="section_id" class="custom-select" name="section_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($sections as $sectionId => $sectionName)
                <option value="{{ $sectionId }}" @selected((int) old('section_id', $student?->section_id) === (int) $sectionId)>
                    {{ $sectionName }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-2">
        <label for="guardian_id">{{ trans('Students_trans.parent') }} <span class="text-danger">*</span></label>
        <select id="guardian_id" class="custom-select" name="guardian_id">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @foreach ($guardians as $guardian)
                <option value="{{ $guardian->id }}" @selected((int) old('guardian_id', $student?->guardian_id) === $guardian->id)>
                    {{ $guardian->father_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-2">
        <label for="academic_year">{{ trans('Students_trans.academic_year') }} <span class="text-danger">*</span></label>
        <select id="academic_year" class="custom-select" name="academic_year">
            <option value="" selected disabled>{{ trans('Parent_trans.Choose') }} ...</option>
            @php
                $currentYear = (int) date('Y');
            @endphp
            @for ($year = $currentYear; $year <= $currentYear + 1; $year++)
                <option value="{{ $year }}" @selected((string) old('academic_year', $student?->academic_year) === (string) $year)>
                    {{ $year }}
                </option>
            @endfor
        </select>
    </div>
</div>
