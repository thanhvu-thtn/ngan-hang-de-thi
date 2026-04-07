<?php

namespace App\Services;

use App\Models\User;
use App\Models\Topic; // Nhớ thêm dòng này

use Illuminate\Support\Facades\DB;

class ObjectivePermissionService
{
    /**
     * Kiểm tra xem User có quyền truy cập vào các Objectives này hay không
     * * @param array $objectiveCodes Mảng chứa mã định danh (tag_name) (VD: ['VL1B-TD-KNDT-01', 'VL1B-TD-DTD-01'])
     * @param User $user Đối tượng người dùng đang thao tác
     * @return array Trả về mảng chứa trạng thái hợp lệ và thông báo lỗi
     */
    public function verifyObjectivePermissions(array $objectiveCodes, User $user): array
    {
        // 1. Nếu câu hỏi không gắn chuẩn đầu ra nào -> Mặc định cho Pass
        if (empty($objectiveCodes)) {
            return [
                'is_valid' => true,
                'errors' => []
            ];
        }

        $errors = [];

        // 2. Lấy danh sách topic_id mà User được cấp quyền từ bảng topic_user
        $allowedTopicIds = DB::table('topic_user')
            ->where('user_id', $user->id)
            ->pluck('topic_id')
            ->toArray();

        // 3. Truy vấn tìm các Objective dựa vào tag_name và join để lấy ra topic_id
        $objectives = DB::table('objectives')
            ->join('contents', 'objectives.content_id', '=', 'contents.id')
            ->whereIn('objectives.tag_name', $objectiveCodes)
            ->select('objectives.tag_name as code', 'contents.topic_id')
            ->get();

        $foundCodes = $objectives->pluck('code')->toArray();

        // 4. Kiểm tra xem có mã Objective nào gõ sai/không tồn tại trong hệ thống không
        $missingCodes = array_diff($objectiveCodes, $foundCodes);
        if (!empty($missingCodes)) {
            $errors[] = 'Các mã yêu cầu cần đạt không tồn tại trong hệ thống: ' . implode(', ', $missingCodes);
        }

        // 5. Kiểm tra quyền truy cập: Objective đó có thuộc Topic mà User được phân công không?
        $unauthorizedCodes = [];
        foreach ($objectives as $obj) {
            if (!in_array($obj->topic_id, $allowedTopicIds)) {
                $unauthorizedCodes[] = $obj->code;
            }
        }

        if (!empty($unauthorizedCodes)) {
            $errors[] = 'Bạn không có quyền thao tác với các yêu cầu cần đạt: ' . implode(', ', $unauthorizedCodes);
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Lấy cây Mục tiêu đánh giá (Yêu cầu cần đạt) mà User được quyền thao tác
     * * @param User $user
     * @param bool $withQuestionCount Có đếm số lượng câu hỏi bên trong mỗi objective không
     * @return \Illuminate\Support\Collection Cây dữ liệu đã được group theo 'grade'
     */
    public function getAllowedObjectiveTree(User $user, bool $withQuestionCount = false)
    {
        $query = Topic::with(['contents.objectives' => function ($q) use ($withQuestionCount) {
            // Chỉ đếm số lượng câu hỏi nếu được yêu cầu (như ở màn hình Index)
            if ($withQuestionCount) {
                $q->withCount('questions');
            }
        }]);

        if ($user->hasRole(['admin', 'Admin'])) {
            // Admin thấy toàn bộ
        } elseif ($user->hasRole('Tổ trưởng')) {
            // Tổ trưởng thấy theo môn
            $query->where('subject_id', $user->subject_id);
        } else {
            // Giáo viên thấy theo phân công
            $assignedTopicIds = $user->topics()->pluck('topics.id');
            if ($assignedTopicIds->isEmpty()) {
                return collect(); // Trả về collection rỗng luôn cho lẹ
            }
            $query->whereIn('id', $assignedTopicIds);
        }

        $topics = $query->orderBy('grade')->orderBy('order')->get();

        // Gom nhóm theo Khối (grade) ngay tại Service và trả về luôn
        return $topics->groupBy('grade');
    }
}