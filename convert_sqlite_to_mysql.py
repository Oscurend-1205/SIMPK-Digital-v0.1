import sqlite3
import re

def convert_sqlite_to_mysql(sqlite_db_path, mysql_sql_path):
    conn = sqlite3.connect(sqlite_db_path)
    
    with open(mysql_sql_path, 'w', encoding='utf-8') as f:
        f.write("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';\n")
        f.write("START TRANSACTION;\n")
        f.write("SET time_zone = '+00:00';\n")
        f.write("SET FOREIGN_KEY_CHECKS = 0;\n\n")
        
        # Get all tables
        cursor = conn.cursor()
        cursor.execute("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence';")
        tables = cursor.fetchall()
        
        for table in tables:
            table_name = table[0]
            
            # Drop table if exists
            f.write(f"DROP TABLE IF EXISTS `{table_name}`;\n")
            
            # Get table creation sql
            cursor.execute(f"SELECT sql FROM sqlite_master WHERE type='table' AND name='{table_name}';")
            create_sql = cursor.fetchone()[0]
            
            # Modify create_sql to mysql format
            # Replace "table_name" with `table_name`
            create_sql = re.sub(r'"([^"]+)"', r'`\1`', create_sql)
            # Case insensitive replace of autoincrement
            create_sql = re.sub(r'(?i)autoincrement', 'AUTO_INCREMENT', create_sql)
            # Replace varchar without length
            create_sql = re.sub(r'(?i)\bvarchar\b(?!\()', 'VARCHAR(255)', create_sql)
            # Remove CHECK constraints to avoid compatibility issues in older MySQL
            # This handles one level of nested parentheses like check (col in ('a', 'b'))
            create_sql = re.sub(r'(?i)\s+check\s*\([^()]*(?:\([^()]*\)[^()]*)*\)', '', create_sql)
            
            f.write(f"{create_sql};\n\n")
            
            # Dump data
            cursor.execute(f"SELECT * FROM `{table_name}`")
            rows = cursor.fetchall()
            
            if rows:
                # get column names
                cursor.execute(f"PRAGMA table_info(`{table_name}`)")
                columns = [col[1] for col in cursor.fetchall()]
                columns_str = ", ".join([f"`{col}`" for col in columns])
                
                # Split into chunks of 100 rows
                chunk_size = 100
                for i in range(0, len(rows), chunk_size):
                    chunk = rows[i:i + chunk_size]
                    values_list = []
                    for row in chunk:
                        # Format values
                        formatted_row = []
                        for val in row:
                            if val is None:
                                formatted_row.append("NULL")
                            elif isinstance(val, (int, float)):
                                formatted_row.append(str(val))
                            else:
                                # Escape string
                                val_str = str(val).replace("'", "''")
                                val_str = val_str.replace("\\", "\\\\")
                                formatted_row.append(f"'{val_str}'")
                        values_list.append("(" + ", ".join(formatted_row) + ")")
                    
                    insert_sql = f"INSERT INTO `{table_name}` ({columns_str}) VALUES\n" + ",\n".join(values_list) + ";\n"
                    f.write(insert_sql)
            
            f.write("\n")
            
        f.write("SET FOREIGN_KEY_CHECKS = 1;\n")
        f.write("COMMIT;\n")

    conn.close()
    print("Conversion complete!")

if __name__ == "__main__":
    convert_sqlite_to_mysql('database/database.sqlite', 'database/database_mysql.sql')
