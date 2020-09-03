-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [bib].[Sp_DEL_tipo_bien]
    @_iTipoBienId INTEGER
AS
BEGIN
    SET NOCOUNT ON;

    DELETE FROM bib.tipo_bien WHERE iTipoBienId=@_iTipoBienId

    IF @@ROWCOUNT>0
		BEGIN
        SELECT 1 iResult
        RETURN 1
    END
    ErrorCapturado:
    SELECT 0 iResult
    RETURN 0
END
GO
