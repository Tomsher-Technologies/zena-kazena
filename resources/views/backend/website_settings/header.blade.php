@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">Website Header</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8 mx-auto">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">Header Setting</h6>
			</div>
			<div class="card-body">
				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
					@csrf
					
                    
					<div class=" pt-3">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">Help Line Title</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="helpline_title">
									<input type="text" class="form-control" placeholder="Help line title" name="helpline_title" value="{{ get_setting('helpline_title') }}">
								</div>
							</div>
						</div>
                    </div>

                    <div class="">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">Help line number</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="helpline_number">
									<input type="text" class="form-control" placeholder="Help line number" name="helpline_number" value="{{ get_setting('helpline_number') }}">
								</div>
							</div>
						</div>
                    </div>
					<div class="form-group row ">
						<h6 class="col-md-12 col-from-label"> Category Menu Settings</h6>
					</div>
					<div class="form-group row ">
	                    <label class="col-md-3 col-from-label">Category Image</label>
						<div class="col-md-8">
		                    <div class=" input-group " data-toggle="aizuploader" data-type="image">
		                        <div class="input-group-prepend">
		                            <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
		                        </div>
		                        <div class="form-control file-amount">Choose File</div>
								<input type="hidden" name="types[]" value="header_category_logo">
		                        <input type="hidden" name="header_category_logo" class="selected-files" value="{{ get_setting('header_category_logo') }}">
		                    </div>
		                    <div class="file-preview"></div>
						</div>
	                </div>
					<div class="">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">Shop by Category Title</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="header_category_title_1">
									<input type="text" class="form-control" placeholder="Shop by Category Title" name="header_category_title_1" value="{{ get_setting('header_category_title_1') }}">
								</div>
							</div>
						</div>
                    </div>

					<div class="form-group ">
						<label>Categories (Max 12)</label>
						<div class="new_collection-categories-target">
							<input type="hidden" name="types[]" value="header_categories">
							<input type="hidden" name="page_type" value="new_collection">
							
							@if (get_setting('header_categories') != null && get_setting('header_categories') != 'null')
								@foreach (json_decode(get_setting('header_categories'), true) as $key => $value)
									<div class="row gutters-5">
										<div class="col">
											<div class="form-group">
												<select class="form-control aiz-selectpicker" name="header_categories[]" data-live-search="true" data-selected={{ $value }}
													required>
													<option value="">Select Category</option>
													@foreach ($categories as $category)
														<option value="{{ $category->id }}">{{ $category->name }}
														</option>
														@foreach ($category->childrenCategories as $childCategory)
															@include('backend.categories.child_category', [
																'child_category' => $childCategory,
															])
														@endforeach
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-auto">
											<button type="button"
												class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
												data-toggle="remove-parent" data-parent=".row">
												<i class="las la-times"></i>
											</button>
										</div>
									</div>
								@endforeach
							@endif
						</div>
						<button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
							data-content='<div class="row gutters-5">
							<div class="col">
								<div class="form-group">
									<select class="form-control aiz-selectpicker" name="header_categories[]" data-live-search="true" required>
										<option value="">Select Category</option>
										@foreach ($categories as $key => $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
										@foreach ($category->childrenCategories as $childCategory)
										@include('backend.categories.child_category', [
											'child_category' => $childCategory,
										])
										@endforeach
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-auto">
								<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
									<i class="las la-times"></i>
								</button>
							</div>
						</div>'
							data-target=".new_collection-categories-target">
							Add New
						</button>
					</div>


					<div class="">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">Shop by Brand Title</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="header_category_title_2">
									<input type="text" class="form-control" placeholder="Shop by Brand Title" name="header_category_title_2" value="{{ get_setting('header_category_title_2') }}">
								</div>
							</div>
						</div>
                    </div>

					<div class="form-group">
						<label>Brands (Max 12)</label>
						<div class="home-brands-target">
							<input type="hidden" name="types[]" value="header_brands">
							<input type="hidden" name="page_type" value="header_brands">
							
							@if (get_setting('header_brands') != null && get_setting('header_brands') != 'null') 
								@foreach (json_decode(get_setting('header_brands'), true) as $key => $value)
									<div class="row gutters-5">
										<div class="col">
											<div class="form-group">
												<select class="form-control aiz-selectpicker" name="header_brands[]"
													data-live-search="true"  title="Select Brands" data-selected={{ $value }}
													required>
												
													@foreach ($brands as $brand)
														<option value="{{ $brand->id }}">{{ $brand->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-auto">
											<button type="button"
												class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
												data-toggle="remove-parent" data-parent=".row">
												<i class="las la-times"></i>
											</button>
										</div>
									</div>
								@endforeach
							@endif
						</div>
						<button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
							data-content='<div class="row gutters-5">
							<div class="col">
								<div class="form-group">
									<select class="form-control aiz-selectpicker" name="header_brands[]" data-live-search="true"  title="Select Brands" required>
										
										@foreach ($brands as $key => $brand)
										<option value="{{ $brand->id }}">{{ $brand->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-auto">
								<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
									<i class="las la-times"></i>
								</button>
							</div>
						</div>'
							data-target=".home-brands-target">
							Add New
						</button>
					</div>

					<div class="">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">Shop by Occassion Title</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="header_category_title_3">
									<input type="text" class="form-control" placeholder="Shop by Occassion Title" name="header_category_title_3" value="{{ get_setting('header_category_title_3') }}">
								</div>
							</div>
						</div>
                    </div>

					<div class="form-group">
						<label>Occasions (Max 12)</label>
						<div class="home-categories-target">
							<input type="hidden" name="types[]" value="header_occasions">
							<input type="hidden" name="page_type" value="header_occasions">
							
							@if (get_setting('header_occasions') != null && get_setting('header_occasions') != 'null') 
								@foreach (json_decode(get_setting('header_occasions'), true) as $key => $value)
									<div class="row gutters-5">
										<div class="col">
											<div class="form-group">
												<select class="form-control aiz-selectpicker" name="header_occasions[]"
													data-live-search="true"  title="Select Occasions" data-selected={{ $value }}
													required>
												
													@foreach ($occasions as $occasion)
														<option value="{{ $occasion->id }}">{{ $occasion->name }}
														</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-auto">
											<button type="button"
												class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
												data-toggle="remove-parent" data-parent=".row">
												<i class="las la-times"></i>
											</button>
										</div>
									</div>
								@endforeach
							@endif
						</div>
						<button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
							data-content='<div class="row gutters-5">
							<div class="col">
								<div class="form-group">
									<select class="form-control aiz-selectpicker" name="header_occasions[]" data-live-search="true"  title="Select Occasions" required>
										
										@foreach ($occasions as $key => $occasion)
										<option value="{{ $occasion->id }}">{{ $occasion->name }}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-auto">
								<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
									<i class="las la-times"></i>
								</button>
							</div>
						</div>'
							data-target=".home-categories-target">
							Add New
						</button>
					</div>

					
					<div class="text-right">
						<button type="submit" class="btn btn-primary">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
