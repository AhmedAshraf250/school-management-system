<script>
    $(document).ready(function() {
        const classroomUrlTemplate = @json(route('students.getClassrooms', ['id' => '__id__']));
        const sectionUrlTemplate = @json(route('students.getSections', ['id' => '__id__']));
        const chooseLabel = @json(trans('Parent_trans.Choose') . ' ...');
        const oldGradeId = @json(old('grade_id', $student?->grade_id));
        const oldClassroomId = @json(old('classroom_id', $student?->classroom_id));
        const oldSectionId = @json(old('section_id', $student?->section_id));

        const gradeSelect = $('#grade_id');
        const classroomSelect = $('#classroom_id');
        const sectionSelect = $('#section_id');

        // Keep dependent selects reset when no parent value is selected.
        function setEmptyOptions() {
            classroomSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
            sectionSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
        }

        function buildUrl(template, id) {
            return template.replace('__id__', id);
        }

        function loadClassrooms(gradeId, selectedClassroomId = null, selectedSectionId = null) {
            if (!gradeId) {
                setEmptyOptions();
                return;
            }

            $.getJSON(buildUrl(classroomUrlTemplate, gradeId), function(data) {
                classroomSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');

                $.each(data, function(key, value) {
                    const selected = selectedClassroomId && String(selectedClassroomId) ===
                        String(key) ?
                        'selected' : '';
                    classroomSelect.append('<option value="' + key + '" ' + selected + '>' +
                        value +
                        '</option>');
                });

                if (selectedClassroomId) {
                    loadSections(selectedClassroomId, selectedSectionId);
                }
            });
        }

        function loadSections(classroomId, selectedSectionId = null) {
            if (!classroomId) {
                sectionSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');
                return;
            }

            $.getJSON(buildUrl(sectionUrlTemplate, classroomId), function(data) {
                sectionSelect.html('<option value="" selected disabled>' + chooseLabel + '</option>');

                $.each(data, function(key, value) {
                    const selected = selectedSectionId && String(selectedSectionId) === String(
                            key) ?
                        'selected' : '';
                    sectionSelect.append('<option value="' + key + '" ' + selected + '>' +
                        value +
                        '</option>');
                });
            });
        }

        gradeSelect.on('change', function() {
            setEmptyOptions();
            loadClassrooms($(this).val());
        });

        classroomSelect.on('change', function() {
            loadSections($(this).val());
        });

        if (oldGradeId) {
            loadClassrooms(oldGradeId, oldClassroomId, oldSectionId);
        } else {
            setEmptyOptions();
        }
    });
</script>
