@extends('owner.layouts.app')

@section('title', 'Add Amenity')

@section('content')
<!-- Add Amenity Container -->
<div style="max-width: 900px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Back Button -->
    <a href="{{ route('owner.amenities.index') }}" style="color: #667eea; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 2rem; transition: all 0.3s ease;" onmouseover="this.style.color='#764ba2'" onmouseout="this.style.color='#667eea'">
        <i class="fas fa-arrow-left"></i>Back to Amenities
    </a>

    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-concierge-bell" style="font-size: 2.5rem; opacity: 0.95;"></i>
            <div>
                <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Add New Amenity</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Create a new amenity for your hotel</p>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div style="background: linear-gradient(135deg, #ef444415 0%, #dc262615 100%); border-left: 5px solid #ef4444; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #991b1b; font-weight: 700; margin-bottom: 0.75rem;"><i class="fas fa-exclamation-circle mr-2"></i>Please fix the following errors:</div>
        <ul style="margin: 0; padding-left: 1.5rem; color: #7f1d1d;">
            @foreach($errors->all() as $error)
            <li style="margin-bottom: 0.5rem;">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form Card -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #f0f0f0; padding: 2.5rem; animation: slideInUp 0.5s ease-out;">
        
        <form action="{{ route('owner.amenities.store') }}" method="POST">
            @csrf

            <!-- Form Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                
                <!-- Amenity Name Field -->
                <div style="display: flex; flex-direction: column;">
                    <label for="name" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Amenity Name 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                        onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                        onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                        placeholder="e.g., Swimming Pool">
                    @error('name')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>

                <!-- Icon Field -->
                <div style="display: flex; flex-direction: column;">
                    <label for="icon" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                        Icon (Font Awesome Class) 
                        <span style="color: #ef4444; font-weight: 700;">*</span>
                    </label>
                    <div style="display: flex; gap: 0.5rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;" id="iconPreview">
                            <i class="{{ old('icon', 'fa-check') }}"></i>
                        </div>
                        <input type="text" name="icon" id="icon" value="{{ old('icon', 'fa-check') }}" required
                            style="flex: 1; padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit;" 
                            onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                            onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                            onchange="updateIconPreview()"
                            oninput="updateIconPreview()"
                            placeholder="fa-check">
                    </div>
                    <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">Example: fa-wifi, fa-swimming-pool, fa-parking</p>
                    @error('icon')
                        <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Description Field -->
            <div style="display: flex; flex-direction: column; margin-bottom: 1.5rem;">
                <label for="description" style="font-weight: 700; color: #333; margin-bottom: 0.75rem; font-size: 0.95rem;">
                    Description
                </label>
                <textarea name="description" id="description" rows="4"
                    style="padding: 0.75rem 1rem; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; resize: vertical;" 
                    onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 3px rgba(102, 126, 234, 0.1)'"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'"
                    placeholder="Describe this amenity and its benefits to guests...">{{ old('description') }}</textarea>
                @error('description')
                    <span style="color: #dc2626; font-size: 0.85rem; margin-top: 0.5rem;"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
                @enderror
            </div>

            <!-- Active Checkbox -->
            <div style="background: #f9fafb; padding: 1.5rem; border-radius: 10px; margin-bottom: 2rem; border: 2px solid #f0f0f0; transition: all 0.3s ease;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        style="width: 20px; height: 20px; cursor: pointer; accent-color: #667eea;">
                    <div>
                        <p style="margin: 0; font-weight: 700; color: #333;">Active</p>
                        <p style="margin: 0.25rem 0 0 0; color: #666; font-size: 0.85rem;">This amenity will be visible to guests</p>
                    </div>
                </label>
            </div>

            <!-- Divider -->
            <div style="height: 1px; background: linear-gradient(90deg, transparent, #e5e7eb, transparent); margin: 2rem 0;"></div>

            <!-- Form Actions -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('owner.amenities.index') }}" style="background: #f3f4f6; color: #374151; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.backgroundColor='#e5e7eb'" onmouseout="this.style.backgroundColor='#f3f4f6'">
                    <i class="fas fa-times"></i>Cancel
                </a>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 30px rgba(102, 126, 234, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.3)'">
                    <i class="fas fa-plus"></i>Add Amenity
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    function updateIconPreview() {
        const iconInput = document.getElementById('icon').value;
        const iconPreview = document.getElementById('iconPreview');
        iconPreview.innerHTML = '<i class="' + iconInput + '"></i>';
    }
</script>
@endsection
