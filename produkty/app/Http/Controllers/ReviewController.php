<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);

        // Sprawdź czy użytkownik już ocenił ten produkt (opcjonalne)
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Już oceniłeś ten produkt.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Opinia dodana!');
    }
}