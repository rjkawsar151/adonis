@extends('layouts.admin')

@section('title', 'Edit Job: ' . $career->title)
@section('page_title', 'Modify Job Posting')

@section('admin_content')
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<form action="{{ route('admin.careers.update', $career->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Fields -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Job details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Job Title</label>
                        <input type="text" name="title" id="job-title" value="{{ old('title', $career->title) }}" required placeholder="e.g. Master Barber" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">URL Slug</label>
                        <input type="text" name="slug" id="job-slug" value="{{ old('slug', $career->slug) }}" required placeholder="e.g. master-barber" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Short Description</label>
                    <textarea name="short_description" rows="3" placeholder="Brief summary displayed on job cards" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">{{ old('short_description', $career->short_description) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Full Job Description</label>
                    <textarea name="description" id="editor-description" rows="5">{!! old('description', $career->description) !!}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Job Responsibilities</label>
                    <textarea name="responsibilities" id="editor-responsibilities" rows="5">{!! old('responsibilities', $career->responsibilities) !!}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Educational Requirements</label>
                        <textarea name="educational_requirements" id="editor-education" rows="4">{!! old('educational_requirements', $career->educational_requirements) !!}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Experience Requirements</label>
                        <textarea name="experience_requirements" id="editor-experience" rows="4">{!! old('experience_requirements', $career->experience_requirements) !!}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Additional Requirements</label>
                        <textarea name="additional_requirements" id="editor-additional" rows="4">{!! old('additional_requirements', $career->additional_requirements) !!}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Benefits</label>
                        <textarea name="benefits" id="editor-benefits" rows="4">{!! old('benefits', $career->benefits) !!}</textarea>
                    </div>
                </div>
            </div>

            <!-- Dynamic Custom Questions Builder -->
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-gray-800 pb-3 mb-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-white">Custom Application Questions</h3>
                    <button type="button" onclick="addQuestionRow()" class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 text-gold text-xs font-semibold uppercase tracking-wider border border-[#C9A84C]/35">
                        + Add Question
                    </button>
                </div>

                <div id="questions-container" class="space-y-4">
                    @php $questionIndex = 0; @endphp
                    @foreach($career->questions as $q)
                        <div class="p-4 bg-[#0c0f15] border border-gray-800 relative space-y-3" id="question-row-{{ $questionIndex }}">
                            <button type="button" onclick="removeQuestionRow({{ $questionIndex }})" class="absolute top-2 right-2 text-gray-500 hover:text-red-500" aria-label="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Question Title</label>
                                    <input type="text" name="questions[{{ $questionIndex }}][question]" value="{{ $q->question }}" required placeholder="e.g. Do you have scissors?" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Type</label>
                                    <select name="questions[{{ $questionIndex }}][question_type]" onchange="toggleOptions({{ $questionIndex }}, this.value)" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                                        <option value="text" {{ $q->question_type === 'text' ? 'selected' : '' }}>Short Text</option>
                                        <option value="textarea" {{ $q->question_type === 'textarea' ? 'selected' : '' }}>Long Text</option>
                                        <option value="number" {{ $q->question_type === 'number' ? 'selected' : '' }}>Number</option>
                                        <option value="email" {{ $q->question_type === 'email' ? 'selected' : '' }}>Email</option>
                                        <option value="phone" {{ $q->question_type === 'phone' ? 'selected' : '' }}>Phone</option>
                                        <option value="dropdown" {{ $q->question_type === 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                        <option value="radio" {{ $q->question_type === 'radio' ? 'selected' : '' }}>Radio Button</option>
                                        <option value="checkbox" {{ $q->question_type === 'checkbox' ? 'selected' : '' }}>Checkbox</option>
                                        <option value="multiselect" {{ $q->question_type === 'multiselect' ? 'selected' : '' }}>Multiple Selection</option>
                                        <option value="date" {{ $q->question_type === 'date' ? 'selected' : '' }}>Date</option>
                                        <option value="yes_no" {{ $q->question_type === 'yes_no' ? 'selected' : '' }}>Yes or No</option>
                                        <option value="file" {{ $q->question_type === 'file' ? 'selected' : '' }}>File Upload</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Help Text / Instructions</label>
                                    <input type="text" name="questions[{{ $questionIndex }}][help_text]" value="{{ $q->help_text }}" placeholder="Instructions for candidate (optional)" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                                </div>
                                <div id="options-container-{{ $questionIndex }}" class="{{ in_array($q->question_type, ['dropdown', 'radio', 'multiselect']) ? '' : 'hidden' }}">
                                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Options (comma separated)</label>
                                    <input type="text" name="questions[{{ $questionIndex }}][options]" value="{{ is_array($q->options) ? implode(', ', $q->options) : $q->options }}" placeholder="Option 1, Option 2, Option 3" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                                </div>
                            </div>

                            <div class="flex items-center space-x-6">
                                <div class="flex items-center space-x-1.5">
                                    <input type="checkbox" name="questions[{{ $questionIndex }}][is_required]" id="req-{{ $questionIndex }}" value="1" {{ $q->is_required ? 'checked' : '' }} class="h-3.5 w-3.5 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                                    <label for="req-{{ $questionIndex }}" class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider cursor-pointer">Mandatory (Required)</label>
                                </div>
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Display Order:</span>
                                    <input type="number" name="questions[{{ $questionIndex }}][sort_order]" value="{{ $q->sort_order }}" class="w-12 bg-[#111827] border border-gray-800 text-xs text-gray-200 px-2 py-1 text-center">
                                </div>
                            </div>
                        </div>
                        @php $questionIndex++; @endphp
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar Config Fields -->
        <div class="space-y-6">
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Configurations</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Department</label>
                    <select name="department_id" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ $career->department_id === $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Employment Type</label>
                    <select name="employment_type_id" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="">Select Type</option>
                        @foreach($employmentTypes as $type)
                            <option value="{{ $type->id }}" {{ $career->employment_type_id === $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Job Location</label>
                    <input type="text" name="location" value="{{ old('location', $career->location) }}" required placeholder="e.g. Gulshan, Dhaka" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Gender Requirement</label>
                    <select name="gender" required class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="Both" {{ $career->gender === 'Both' ? 'selected' : '' }}>Both (Male & Female)</option>
                        <option value="Male" {{ $career->gender === 'Male' ? 'selected' : '' }}>Male Only</option>
                        <option value="Female" {{ $career->gender === 'Female' ? 'selected' : '' }}>Female Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Vacancy Count</label>
                    <input type="number" name="vacancy" min="1" value="{{ old('vacancy', $career->vacancy) }}" required class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Min Salary</label>
                        <input type="number" name="salary_min" value="{{ old('salary_min', $career->salary_min) }}" placeholder="Min" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Max Salary</label>
                        <input type="number" name="salary_max" value="{{ old('salary_max', $career->salary_max) }}" placeholder="Max" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-3 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Salary Type</label>
                    <input type="text" name="salary_type" value="{{ old('salary_type', $career->salary_type) }}" required class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Application Deadline</label>
                    <input type="date" name="application_deadline" value="{{ $career->application_deadline ? $career->application_deadline->format('Y-m-d') : '' }}" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Featured Image</label>
                    @if($career->featured_image)
                        <div class="mb-2">
                            <img src="{{ asset($career->featured_image) }}" class="h-16 w-auto object-cover border border-gray-800">
                        </div>
                    @endif
                    <input type="file" name="featured_image" accept="image/*" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Skills Required</label>
                    <input type="text" name="skills" value="{{ old('skills', $career->skills) }}" placeholder="Comma separated, e.g. Barbering, Grooming" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">Status</label>
                    <select name="status" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                        <option value="draft" {{ $career->status === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ $career->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $career->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="closed" {{ $career->status === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ $career->is_featured ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                    <label for="is_featured" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Mark as Featured Job</label>
                </div>
            </div>

            <!-- Form Visibility Toggles -->
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">Application Form Config</h3>
                <p class="text-[10px] text-gray-500 uppercase tracking-widest mb-3">Check the fields you want to show on the public application form:</p>
                
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_address" id="show_address" value="1" {{ $career->show_address ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_address" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Present Address</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_linkedin" id="show_linkedin" value="1" {{ $career->show_linkedin ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_linkedin" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">LinkedIn Profile URL</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_portfolio" id="show_portfolio" value="1" {{ $career->show_portfolio ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_portfolio" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Portfolio Link (Optional)</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_current_company" id="show_current_company" value="1" {{ $career->show_current_company ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_current_company" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Current Company</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_current_designation" id="show_current_designation" value="1" {{ $career->show_current_designation ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_current_designation" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Current Designation</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_expected_salary" id="show_expected_salary" value="1" {{ $career->show_expected_salary ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_expected_salary" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Expected Salary</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_joining_date" id="show_joining_date" value="1" {{ $career->show_joining_date ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_joining_date" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Available Joining Date</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="show_cover_letter" id="show_cover_letter" value="1" {{ $career->show_cover_letter ? 'checked' : '' }} class="h-4 w-4 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                        <label for="show_cover_letter" class="text-xs font-semibold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Cover Letter</label>
                    </div>
                </div>
            </div>

            <!-- SEO Configuration -->
            <div class="bg-[#111827] border border-gray-800 p-6 space-y-4">
                <h3 class="text-sm font-bold uppercase tracking-wider text-white border-b border-gray-800 pb-3 mb-4">SEO Configuration</h3>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">SEO Title</label>
                    <input type="text" name="seo_title" value="{{ old('seo_title', $career->seo_title) }}" placeholder="Search engine title display" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1.5">SEO Description</label>
                    <textarea name="seo_description" rows="3" placeholder="Search engine meta description" class="w-full bg-[#0c0f15] border border-gray-800 text-sm text-gray-200 px-4 py-2.5 focus:outline-none focus:border-[#C9A84C]">{{ old('seo_description', $career->seo_description) }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full py-4 bg-[#C9A84C] hover:bg-[#b8973f] text-black font-serif text-sm font-bold uppercase tracking-widest transition-colors shadow-md">
                Update Career Opening
            </button>
        </div>
    </div>
</form>

<script>
    // Initialize CKEditor
    const editorConfig = {
        versionCheck: false,
        removePlugins: 'about',
        toolbar: [
            { name: 'document', items: [ 'Source', '-', 'Undo', 'Redo' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'insert', items: [ 'Table', 'HorizontalRule' ] },
            { name: 'styles', items: [ 'Styles', 'Format' ] }
        ]
    };
    CKEDITOR.replace('editor-description', editorConfig);
    CKEDITOR.replace('editor-responsibilities', editorConfig);
    CKEDITOR.replace('editor-education', editorConfig);
    CKEDITOR.replace('editor-experience', editorConfig);
    CKEDITOR.replace('editor-additional', editorConfig);
    CKEDITOR.replace('editor-benefits', editorConfig);

    // Auto slug generator
    document.getElementById('job-title').addEventListener('input', function(e) {
        document.getElementById('job-slug').value = e.target.value
            .toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
    });

    // Custom questions builder row manager
    let questionIndex = {{ $questionIndex }};
    
    function addQuestionRow() {
        const container = document.getElementById('questions-container');
        const row = document.createElement('div');
        row.className = 'p-4 bg-[#0c0f15] border border-gray-800 relative space-y-3';
        row.id = `question-row-${questionIndex}`;

        row.innerHTML = `
            <button type="button" onclick="removeQuestionRow(${questionIndex})" class="absolute top-2 right-2 text-gray-500 hover:text-red-500" aria-label="Delete">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <div class="grid grid-cols-3 gap-3">
                <div class="col-span-2">
                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Question Title</label>
                    <input type="text" name="questions[${questionIndex}][question]" required placeholder="e.g. Do you have a personal set of professional scissors?" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Type</label>
                    <select name="questions[${questionIndex}][question_type]" onchange="toggleOptions(${questionIndex}, this.value)" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                        <option value="text">Short Text</option>
                        <option value="textarea">Long Text</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="phone">Phone</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="radio">Radio Button</option>
                        <option value="checkbox">Checkbox</option>
                        <option value="multiselect">Multiple Selection</option>
                        <option value="date">Date</option>
                        <option value="yes_no">Yes or No</option>
                        <option value="file">File Upload</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Help Text / Instructions</label>
                    <input type="text" name="questions[${questionIndex}][help_text]" placeholder="Instructions for candidate (optional)" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                </div>
                <div id="options-container-${questionIndex}" class="hidden">
                    <label class="block text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1">Options (comma separated)</label>
                    <input type="text" name="questions[${questionIndex}][options]" placeholder="Option 1, Option 2, Option 3" class="w-full bg-[#111827] border border-gray-800 text-xs text-gray-200 px-3 py-2 focus:outline-none focus:border-[#C9A84C]">
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-1.5">
                    <input type="checkbox" name="questions[${questionIndex}][is_required]" id="req-${questionIndex}" value="1" class="h-3.5 w-3.5 bg-black border-gray-800 text-[#C9A84C] focus:ring-0">
                    <label for="req-${questionIndex}" class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider cursor-pointer">Mandatory (Required)</label>
                </div>
                <div class="flex items-center space-x-1.5">
                    <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Display Order:</span>
                    <input type="number" name="questions[${questionIndex}][sort_order]" value="${questionIndex}" class="w-12 bg-[#111827] border border-gray-800 text-xs text-gray-200 px-2 py-1 text-center">
                </div>
            </div>
        `;
        container.appendChild(row);
        questionIndex++;
    }

    function removeQuestionRow(idx) {
        document.getElementById(`question-row-${idx}`).remove();
    }

    function toggleOptions(idx, type) {
        const optCont = document.getElementById(`options-container-${idx}`);
        if (['dropdown', 'radio', 'multiselect'].includes(type)) {
            optCont.classList.remove('hidden');
        } else {
            optCont.classList.add('hidden');
        }
    }
</script>
@endsection
