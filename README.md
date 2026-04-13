<h1 align="center">🍽️ Sistema Integral de Gestión Gastronómica</h1>
<h2 align="center">"Sabor & Gestión"</h2>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/Status-En%20Desarrollo-yellow?style=for-the-badge" />
</p>

<hr>

<h2>📌 Descripción del Proyecto</h2>
<p>
Aplicación web desarrollada con <b>Laravel 12</b> orientada a la gestión de un negocio gastronómico.
</p>

<ul>
  <li>📦 Gestión de pedidos</li>
  <li>👤 Administración de usuarios</li>
  <li>⚙️ Procesos internos</li>
</ul>

<hr>

<h2>🛠️ Tecnologías</h2>
<ul>
  <li>PHP 8.2</li>
  <li>Laravel 12</li>
  <li>MySQL</li>
  <li>Git & GitHub</li>
  <li>Node.js + Vite</li>
</ul>

<hr>

<h2>🌿 Estrategia de Ramas</h2>

<pre>
main      → versión estable
develop   → integración
feature/* → nuevas funcionalidades
</pre>

<p><b>Ejemplos:</b></p>
<pre>
feature/login
feature/pedidos
</pre>

<hr>

<h2>🔗 Repositorio</h2>
<p>
<a href="https://github.com/john33-tech/TIS-1-2026-Grp4.git">
https://github.com/john33-tech/TIS-1-2026-Grp4.git
</a>
</p>

<hr>

<h2>🔄 Flujo de Trabajo</h2>

<pre>
1. Crear rama desde develop
2. Desarrollar funcionalidad
3. Commit
4. Push
5. Pull Request
6. Revisión
7. Merge a develop
8. Paso a main
</pre>

<hr>

<h2>⚙️ Instalación</h2>

<h3>1. Clonar repositorio</h3>
<pre>
git clone https://github.com/john33-tech/TIS-1-2026-Grp4.git
cd TIS-1-2026-Grp4
</pre>

<h3>2. Instalar dependencias</h3>
<pre>
composer install
npm install
</pre>

<h3>3. Configurar entorno</h3>
<pre>
cp .env.example .env
php artisan key:generate
</pre>

<h3>4. Base de datos</h3>
<p>Editar archivo <b>.env</b>:</p>

<pre>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saborgestion
DB_USERNAME=root
DB_PASSWORD=
</pre>

<p>Crear base de datos:</p>

<pre>
CREATE DATABASE saborgestion;
</pre>

<h3>5. Migraciones</h3>
<pre>
php artisan migrate
</pre>

<h3>6. Ejecutar proyecto</h3>
<pre>
npm run dev
php artisan serve
</pre>

<hr>

<h2>🚀 Scripts</h2>

<pre>
composer run dev
composer run test
</pre>

<hr>

<h2>📦 Estructura</h2>

<pre>
app/
routes/
database/
resources/
public/
</pre>

<hr>

<h2>🔐 Configuración</h2>
<ul>
  <li>Base de datos: MySQL</li>
  <li>Puerto: 3306</li>
  <li>BD: saborgestion</li>
  <li>URL: http://localhost:8000</li>
</ul>

<hr>

<h2>📊 Metodología</h2>
<ul>
  <li>Scrum Master</li>
  <li>Front-end</li>
  <li>Back-end</li>
  <li>QA</li>
  <li>DevOps</li>

</ul>

<hr>

<h2>📌 Estado</h2>
<p>🟡 En desarrollo</p>

<hr>

<h2>✍️ Autor</h2>
<p>Equipo de desarrollo<br>Proyecto académico</p>