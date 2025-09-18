<?php
namespace Webkul\DeliveryAgents\Helpers;
use Illuminate\Support\Facades\DB;

class DeliveryAgentReview
{
    public function getReviews($agent)
    {
        return $agent->reviews()->where('status', 'approved');
    }

    public function getAverageRating($agent)
    {
        return number_format(
            round($agent->reviews->where('status', 'approved')->avg('rating'), 2),
            1
        );
    }

    public function getTotalReviews($agent)
    {
        return $agent->reviews->where('status', 'approved')->count();
    }

    public function getTotalRating($agent)
    {
        return $agent->reviews->where('status', 'approved')->sum('rating');
    }

    public function getReviewsWithRatings($agent)
    {
        return $agent->reviews()
            ->where('status', 'approved')
            ->select('rating', DB::raw('count(*) as total'))
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get();
    }

    public function getPercentageRating($agent)
    {
        $reviews = $this->getReviewsWithRatings($agent);

        $totalReviews = $this->getTotalReviews($agent);

        for ($i = 5; $i >= 1; $i--) {
            if (! $reviews->isEmpty()) {
                foreach ($reviews as $review) {
                    if ($review->rating == $i) {
                        $percentage[$i] = round(($review->total / $totalReviews) * 100);
                        break;
                    } else {
                        $percentage[$i] = 0;
                    }
                }
            } else {
                $percentage[$i] = 0;
            }
        }

        return $percentage;
    }

}
