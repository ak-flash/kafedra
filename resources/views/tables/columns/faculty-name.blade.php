<div>
    {{
                    $facultyId = $record->discipline->faculty_id;
                    $faculty = EducationService::getFaculties()
                        ->where('id', $facultyId)->first();

                    $getState()*2 }}
</div>
