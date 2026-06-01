import tkinter as tk
from tkinter import ttk, messagebox
import subprocess


# ─────────────────────────────────────────────
#  Helpers
# ─────────────────────────────────────────────
def ejecutar_comando(comando):
    try:
        return subprocess.check_output(comando, shell=True, stderr=subprocess.DEVNULL).decode(errors="replace").strip()
    except subprocess.CalledProcessError:
        return ""


def obtener_contenido_remoto(remote, archivo):
    return ejecutar_comando(f"git show {remote}/main:{archivo}")


def obtener_contenido_local(archivo):
    try:
        with open(archivo, "r", encoding="utf-8", errors="replace") as f:
            return f.read()
    except FileNotFoundError:
        return ""


# ─────────────────────────────────────────────
#  Ventana de comparación
# ─────────────────────────────────────────────
class VentanaComparador(tk.Toplevel):
    def __init__(self, parent, archivo, remote):
        super().__init__(parent)
        self.title(f"Comparando: {archivo}")
        self.geometry("1200x700")
        self.configure(bg="#1a1a2e")
        self.resizable(True, True)

        self.archivo = archivo
        self.remote = remote

        self._construir_ui()
        self._cargar_diff()

    # ── UI ──────────────────────────────────
    def _construir_ui(self):
        # Header
        header = tk.Frame(self, bg="#0f3460", pady=8)
        header.pack(fill=tk.X)
        tk.Label(header, text=f"📄  {self.archivo}",
                 bg="#0f3460", fg="#e2e8f0",
                 font=("Courier New", 13, "bold")).pack(side=tk.LEFT, padx=16)
        tk.Label(header, text=f"remoto: {self.remote}/main  →  local",
                 bg="#0f3460", fg="#94a3b8",
                 font=("Courier New", 10)).pack(side=tk.LEFT, padx=8)

        # Leyenda
        leyenda = tk.Frame(self, bg="#1a1a2e", pady=4)
        leyenda.pack(fill=tk.X, padx=8)
        for color, texto in [("#2d4a1e", "▐  Sin cambios"),
                              ("#4a1e1e", "▐  Línea eliminada (remoto)"),
                              ("#1e3a4a", "▐  Línea añadida (local)"),
                              ("#4a3a1e", "▐  Línea modificada")]:
            tk.Label(leyenda, text=texto, bg=color, fg="#e2e8f0",
                     font=("Courier New", 9), padx=6, pady=2,
                     relief=tk.FLAT).pack(side=tk.LEFT, padx=4)

        # Paneles
        paned = tk.PanedWindow(self, orient=tk.HORIZONTAL,
                               bg="#1a1a2e", sashwidth=6, sashrelief=tk.FLAT)
        paned.pack(fill=tk.BOTH, expand=True, padx=8, pady=6)

        self.panel_remoto = self._crear_panel(paned, f"🌐  {self.remote}/main  (origen)")
        self.panel_local  = self._crear_panel(paned, "💻  Local  (destino tras descargar)")
        paned.add(self.panel_remoto["frame"], stretch="always")
        paned.add(self.panel_local["frame"],  stretch="always")

        # Sincronizar scroll
        self.panel_remoto["text"].configure(yscrollcommand=self._scroll_sync_remoto)
        self.panel_local["text"].configure( yscrollcommand=self._scroll_sync_local)

        # Barra inferior
        barra = tk.Frame(self, bg="#0f3460", pady=8)
        barra.pack(fill=tk.X)

        self._lbl_stats = tk.Label(barra, text="", bg="#0f3460", fg="#94a3b8",
                                   font=("Courier New", 10))
        self._lbl_stats.pack(side=tk.LEFT, padx=16)

        tk.Button(barra, text="✅  Descargar este archivo",
                  bg="#16a34a", fg="white", activebackground="#15803d",
                  font=("Courier New", 10, "bold"), bd=0, padx=14, pady=4,
                  cursor="hand2", command=self._descargar).pack(side=tk.RIGHT, padx=8)
        tk.Button(barra, text="✖  Cerrar",
                  bg="#dc2626", fg="white", activebackground="#b91c1c",
                  font=("Courier New", 10, "bold"), bd=0, padx=14, pady=4,
                  cursor="hand2", command=self.destroy).pack(side=tk.RIGHT, padx=4)

    def _crear_panel(self, parent, titulo):
        frame = tk.Frame(parent, bg="#0d1117")
        tk.Label(frame, text=titulo, bg="#161b22", fg="#7dd3fc",
                 font=("Courier New", 10, "bold"), anchor="w",
                 pady=5, padx=8).pack(fill=tk.X)

        scroll_y = tk.Scrollbar(frame, orient=tk.VERTICAL)
        scroll_x = tk.Scrollbar(frame, orient=tk.HORIZONTAL)

        text = tk.Text(frame, wrap=tk.NONE, font=("Courier New", 11),
                       bg="#0d1117", fg="#e2e8f0",
                       insertbackground="white", selectbackground="#264f78",
                       bd=0, padx=8, pady=4, state=tk.DISABLED,
                       xscrollcommand=scroll_x.set,
                       yscrollcommand=scroll_y.set)

        scroll_y.config(command=text.yview)
        scroll_x.config(command=text.xview)
        scroll_y.pack(side=tk.RIGHT, fill=tk.Y)
        scroll_x.pack(side=tk.BOTTOM, fill=tk.X)
        text.pack(fill=tk.BOTH, expand=True)

        # Tags de color
        text.tag_configure("igual",      background="#0d1117",  foreground="#e2e8f0")
        text.tag_configure("eliminado",  background="#4a1e1e",  foreground="#fca5a5")
        text.tag_configure("agregado",   background="#1e3a4a",  foreground="#7dd3fc")
        text.tag_configure("modificado", background="#4a3a1e",  foreground="#fde68a")
        text.tag_configure("numero",     foreground="#4b5563",  font=("Courier New", 11))

        return {"frame": frame, "text": text}

    # ── Scroll sincronizado ──────────────────
    def _scroll_sync_remoto(self, *args):
        self.panel_remoto["text"].tk.call("set", self.panel_remoto["text"]._w, "yscrollcommand", *args)
        self.panel_local["text"].yview_moveto(args[0])

    def _scroll_sync_local(self, *args):
        self.panel_local["text"].tk.call("set", self.panel_local["text"]._w, "yscrollcommand", *args)
        self.panel_remoto["text"].yview_moveto(args[0])

    # ── Diff ────────────────────────────────
    def _cargar_diff(self):
        remoto_txt = obtener_contenido_remoto(self.remote, self.archivo)
        local_txt  = obtener_contenido_local(self.archivo)

        lineas_remoto = remoto_txt.splitlines() if remoto_txt else []
        lineas_local  = local_txt.splitlines()  if local_txt  else []

        import difflib
        matcher = difflib.SequenceMatcher(None, lineas_remoto, lineas_local)
        opcodes = matcher.get_opcodes()

        filas_remoto = []
        filas_local  = []
        tags_remoto  = []
        tags_local   = []

        eliminadas = agregadas = modificadas = 0

        for tag, i1, i2, j1, j2 in opcodes:
            if tag == "equal":
                for linea in lineas_remoto[i1:i2]:
                    filas_remoto.append(linea)
                    tags_remoto.append("igual")
                for linea in lineas_local[j1:j2]:
                    filas_local.append(linea)
                    tags_local.append("igual")

            elif tag == "replace":
                modificadas += max(i2 - i1, j2 - j1)
                bloque_r = lineas_remoto[i1:i2]
                bloque_l = lineas_local[j1:j2]
                max_len = max(len(bloque_r), len(bloque_l))
                for k in range(max_len):
                    filas_remoto.append(bloque_r[k] if k < len(bloque_r) else "")
                    tags_remoto.append("modificado" if k < len(bloque_r) else "igual")
                    filas_local.append(bloque_l[k] if k < len(bloque_l) else "")
                    tags_local.append("modificado" if k < len(bloque_l) else "igual")

            elif tag == "delete":
                eliminadas += i2 - i1
                for linea in lineas_remoto[i1:i2]:
                    filas_remoto.append(linea)
                    tags_remoto.append("eliminado")
                    filas_local.append("")
                    tags_local.append("igual")

            elif tag == "insert":
                agregadas += j2 - j1
                for linea in lineas_local[j1:j2]:
                    filas_local.append(linea)
                    tags_local.append("agregado")
                    filas_remoto.append("")
                    tags_remoto.append("igual")

        self._rellenar_panel(self.panel_remoto["text"], filas_remoto, tags_remoto)
        self._rellenar_panel(self.panel_local["text"],  filas_local,  tags_local)

        self._lbl_stats.config(
            text=f"  Modificadas: {modificadas}  |  Solo en remoto: {eliminadas}  |  Solo en local: {agregadas}"
        )

    def _rellenar_panel(self, widget, lineas, tags):
        widget.configure(state=tk.NORMAL)
        widget.delete("1.0", tk.END)
        for i, (linea, tag) in enumerate(zip(lineas, tags), start=1):
            num = f"{i:>4}  "
            widget.insert(tk.END, num, ("numero",))
            widget.insert(tk.END, linea + "\n", (tag,))
        widget.configure(state=tk.DISABLED)

    # ── Acción ──────────────────────────────
    def _descargar(self):
        resp = messagebox.askyesno(
            "Confirmar",
            f"¿Sobreescribir '{self.archivo}' con la versión de {self.remote}/main?",
            parent=self
        )
        if resp:
            subprocess.run(f"git checkout {self.remote}/main -- {self.archivo}", shell=True)
            messagebox.showinfo("Éxito", f"'{self.archivo}' actualizado desde {self.remote}.", parent=self)
            self.destroy()


# ─────────────────────────────────────────────
#  Ventana principal
# ─────────────────────────────────────────────
class App:
    def __init__(self, root):
        self.root = root
        self.root.title("Gestor de Sincronización — Railway / TIS")
        self.root.geometry("700x520")
        self.root.configure(bg="#1a1a2e")
        self.root.resizable(True, True)

        self._construir_ui()

    def _construir_ui(self):
        # Header
        header = tk.Frame(self.root, bg="#0f3460", pady=12)
        header.pack(fill=tk.X)
        tk.Label(header, text="🔀  Gestor de Sincronización Git",
                 bg="#0f3460", fg="#e2e8f0",
                 font=("Courier New", 15, "bold")).pack(side=tk.LEFT, padx=16)

        # Selector de remoto
        ctrl = tk.Frame(self.root, bg="#1a1a2e", pady=10)
        ctrl.pack(fill=tk.X, padx=16)
        tk.Label(ctrl, text="Remoto:", bg="#1a1a2e", fg="#94a3b8",
                 font=("Courier New", 11)).pack(side=tk.LEFT)
        remotos = ejecutar_comando("git remote").splitlines() or ["(sin remotos)"]
        self.remote_var = tk.StringVar(value=remotos[0])
        combo = ttk.Combobox(ctrl, textvariable=self.remote_var,
                             values=remotos, state="readonly", width=22,
                             font=("Courier New", 11))
        combo.pack(side=tk.LEFT, padx=8)
        tk.Button(ctrl, text="🔄  Comparar",
                  bg="#0ea5e9", fg="white", activebackground="#0284c7",
                  font=("Courier New", 10, "bold"), bd=0, padx=12, pady=4,
                  cursor="hand2", command=self.cargar_archivos).pack(side=tk.LEFT, padx=4)

        # Estado
        self.lbl_estado = tk.Label(self.root, text="Selecciona un remoto y pulsa Comparar",
                                   bg="#1a1a2e", fg="#4b5563",
                                   font=("Courier New", 10))
        self.lbl_estado.pack(anchor="w", padx=16)

        # Tabla
        tabla_frame = tk.Frame(self.root, bg="#0d1117", bd=0)
        tabla_frame.pack(fill=tk.BOTH, expand=True, padx=16, pady=(4, 8))

        style = ttk.Style()
        style.theme_use("clam")
        style.configure("Dark.Treeview",
                         background="#0d1117", foreground="#e2e8f0",
                         rowheight=26, fieldbackground="#0d1117",
                         font=("Courier New", 11))
        style.configure("Dark.Treeview.Heading",
                         background="#161b22", foreground="#7dd3fc",
                         font=("Courier New", 11, "bold"), relief="flat")
        style.map("Dark.Treeview", background=[("selected", "#264f78")])

        scroll = tk.Scrollbar(tabla_frame)
        scroll.pack(side=tk.RIGHT, fill=tk.Y)

        self.tree = ttk.Treeview(tabla_frame, columns=("archivo", "estado"),
                                 show="headings", style="Dark.Treeview",
                                 yscrollcommand=scroll.set)
        self.tree.heading("archivo", text="Archivo")
        self.tree.heading("estado", text="Estado")
        self.tree.column("archivo", width=460)
        self.tree.column("estado", width=120, anchor="center")
        self.tree.pack(fill=tk.BOTH, expand=True)
        scroll.config(command=self.tree.yview)
        self.tree.bind("<Double-1>", self.abrir_comparador)

        # Barra inferior
        barra = tk.Frame(self.root, bg="#0f3460", pady=8)
        barra.pack(fill=tk.X)
        tk.Label(barra, text="Doble clic en un archivo para comparar",
                 bg="#0f3460", fg="#94a3b8",
                 font=("Courier New", 9)).pack(side=tk.LEFT, padx=16)
        tk.Button(barra, text="✖  Salir",
                  bg="#dc2626", fg="white", activebackground="#b91c1c",
                  font=("Courier New", 10, "bold"), bd=0, padx=12, pady=4,
                  cursor="hand2", command=self.root.destroy).pack(side=tk.RIGHT, padx=8)

    def cargar_archivos(self):
        remote = self.remote_var.get()
        self.lbl_estado.config(text=f"Haciendo fetch de {remote}…", fg="#fbbf24")
        self.root.update()
        ejecutar_comando(f"git fetch {remote}")
        archivos = ejecutar_comando(f"git diff --name-only HEAD {remote}/main").splitlines()
        self.tree.delete(*self.tree.get_children())
        if not archivos:
            self.lbl_estado.config(text="✅  Sin diferencias con el remoto.", fg="#4ade80")
            return
        for f in archivos:
            self.tree.insert("", "end", values=(f, "modificado"))
        self.lbl_estado.config(
            text=f"✔  {len(archivos)} archivo(s) diferente(s) — doble clic para comparar",
            fg="#4ade80"
        )

    def abrir_comparador(self, event):
        item = self.tree.focus()
        if not item:
            return
        archivo = self.tree.item(item)["values"][0]
        VentanaComparador(self.root, archivo, self.remote_var.get())


# ─────────────────────────────────────────────
if __name__ == "__main__":
    root = tk.Tk()
    App(root)
    root.mainloop()
