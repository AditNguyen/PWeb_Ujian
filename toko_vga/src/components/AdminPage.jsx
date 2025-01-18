import React, { useEffect, useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import "./AdminPage.css";


const AdminPage = () => {
  const [cars, setCars] = useState([]);
  const [form, setForm] = useState({
    id: "",
    nama_vga: "",
    merek: "",
    tanggal_rilis: "",
    harga: "",
    foto_url: "",
  });
  const [isEditing, setIsEditing] = useState(false);

  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.removeItem("loggedIn");
    navigate("/");
  };

  const formatHarga = (harga) => {
    return "Rp. " + parseInt(harga, 10).toLocaleString("id-ID");
  };

  const fetchCars = async () => {
    try {
      const response = await axios.get("http://localhost/server/vga.php");
      setCars(response.data);
    } catch (error) {
      console.error("Error fetching vga:", error);
    }
  };

  useEffect(() => {
    fetchCars();
  }, []);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const hargaNumerical = form.harga.replace(/\./g, ""); // Hilangkan titik dari harga
      const updatedForm = { ...form, harga: hargaNumerical };

      if (isEditing) {
        await axios.put("http://localhost/server/vga.php", updatedForm);
        alert("Data mobil berhasil diperbarui!");
      } else {
        await axios.post("http://localhost/server/vga.php", updatedForm);
        alert("Mobil berhasil ditambahkan!");
      }
      fetchCars();
      setForm({ id: "", nama_vga: "", merek: "", tanggal_rilis: "", harga: "", foto_url: "" });
      setIsEditing(false);
    } catch (error) {
      console.error("Error saving vga:", error);
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete("http://localhost/server/vga.php", { data: { id } });
      alert("Mobil berhasil dihapus!");
      fetchCars();
    } catch (error) {
      console.error("Error deleting vga:", error);
    }
  };

  const handleEdit = (car) => {
    setForm(car);
    setIsEditing(true);
  };

  const handleHargaChange = (e) => {
    const rawValue = e.target.value.replace(/[^0-9]/g, ""); // Hanya angka
    const formattedValue = rawValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    setForm({ ...form, harga: formattedValue });
  };

  const handleFotoUrlChange = (e) => {
    setForm({ ...form, foto_url: e.target.value });
  };

  return (
    <div>
      <h2>Admin Page</h2>
      <button onClick={handleLogout}>Logout</button>
      <form onSubmit={handleSubmit}>
        <input
          type="text"
          placeholder="Nama vga"
          value={form.nama_vga}
          onChange={(e) => setForm({ ...form, nama_vga: e.target.value })}
          required
        />
        <input
          type="text"
          placeholder="Merek"
          value={form.merek}
          onChange={(e) => setForm({ ...form, merek: e.target.value })}
          required
        />
        <input
          type="date"
          placeholder="Tanggal Rilis"
          value={form.tanggal_rilis}
          onChange={(e) => setForm({ ...form, tanggal_rilis: e.target.value })}
          required
        />
        <input
          type="text"
          placeholder="Harga"
          value={form.harga}
          onChange={handleHargaChange}
          required
        />
        <input
          type="text"
          placeholder="Masukkan URL Foto (Opsional)"
          value={form.foto_url}
          onChange={handleFotoUrlChange}
        />
        <button type="submit">{isEditing ? "Update" : "Tambah"}</button>
      </form>

      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Vga</th>
            <th>Merek</th>
            <th>Tanggal Rilis</th>
            <th>Harga</th>
            <th>Foto</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          {cars.map((car) => (
            <tr key={car.id}>
              <td>{car.id}</td>
              <td>{car.nama_vga}</td>
              <td>{car.merek}</td>
              <td>{car.tanggal_rilis}</td>
              <td>{formatHarga(car.harga)}</td>
              <td>
                {car.foto_url ? (
                  <img src={car.foto_url} alt="Foto Mobil" width="100" />
                ) : (
                  <span>No Foto</span>
                )}
              </td>
              <td>
                <button onClick={() => handleEdit(car)}>Edit</button>
                <button onClick={() => handleDelete(car.id)}>Hapus</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default AdminPage;
