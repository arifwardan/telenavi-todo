# 📌 Todo List API

API untuk mengelola daftar tugas (Todo List) dengan fitur export laporan ke Excel dan endpoint untuk data chart.

## 🚀 Fitur
- **CRUD Todo List** (Create, Read, Update, Delete)
- **Export Excel Report** dengan filter
- **Chart Data API** untuk statistik berdasarkan status, prioritas, dan assignee

## 🛠️ Instalasi
1. **Clone Repository**
   ```sh
   git clone https://github.com/arifwardan/telenavi-todo.git
   cd todo-api
   ```
2. **Instal Dependensi**
   ```sh
   composer install
   ```
3. **Konfigurasi .env**
   ```sh
   cp .env.example .env
   ```
   Ubah konfigurasi database di `.env` sesuai kebutuhan.
4. **Generate Key & Migrasi Database**
   ```sh
   php artisan key:generate
   php artisan migrate --seed
   ```
5. **Jalankan Server**
   ```sh
   php artisan serve
   ```

## 📌 API Endpoint

### **1. Get Todo List**
```
GET /api/todos
```
#### **Query Parameters** (Opsional)
- `title` (string) - Pencarian berdasarkan judul (partial match)
- `assignee` (string, dipisahkan koma) - Filter berdasarkan assignee
- `due_date` (range) - Format `start=YYYY-MM-DD&end=YYYY-MM-DD`
- `time_tracked` (range) - Format `min=X&max=X`
- `status` (string, dipisahkan koma) - Filter berdasarkan status (`pending, in_progress, completed`)
- `priority` (string, dipisahkan koma) - Filter berdasarkan prioritas (`low, medium, high`)

#### **Contoh Request**
```
GET /api/todos
```
#### **Contoh Response**
```json
[
    {
        "id": 1,
        "title": "Develop Laravel API",
        "assignee": "Alice Johnson",
        "due_date": "2025-04-10",
        "time_tracked": 120,
        "status": "pending",
        "priority": "high"
    }
]

```

### **2. Export Excel Report**
```
GET /api/todos/export/excel
```
#### **Contoh Request dengan Filter**
```
GET /api/todos/export/excel?priority=high&status=pending&min=10&max=200
```

### **3. Get Chart Data**
#### **Summary berdasarkan Status**
```
GET /api/chart?type=status
```
#### **Contoh Response**
```json
{
  "status_summary": {
    "pending": 10,
    "open": 5,
    "in_progress": 7,
    "completed": 12
  }
}
```

#### **Summary berdasarkan Prioritas**
```
GET /api/chart?type=priority
```
#### **Contoh Response**
```json
{
  "priority_summary": {
    "low": 5,
    "medium": 8,
    "high": 21
  }
}
```

#### **Summary berdasarkan Assignee**
```
GET /api/chart?type=assignee
```
#### **Contoh Response**
```json
{
  "assignee_summary": {
    "John Doe": {
      "total_todos": 5,
      "total_pending_todos": 2,
      "total_timetracked_completed_todos": 120
    }
  }
}
```

## 📜 Lisensi
Proyek ini menggunakan lisensi **MIT**.

